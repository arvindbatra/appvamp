package com.pjab.apper.server;

/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

import org.apache.thrift.TException;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.server.TServer;
import org.apache.thrift.server.TSimpleServer;
import org.apache.thrift.server.TThreadPoolServer;
import org.apache.thrift.transport.TServerSocket;
import org.apache.thrift.transport.TServerTransport;

// Generated code
import ThriftProtocol.*;

import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

import com.pjab.apper.AppConfig;
import com.pjab.apper.Apper;


public class JavaServer {

  public static class AppServerHandler implements AppServer.Iface {
    
	public String query(Map<String,String> qpacket) throws TException
	{
	  	System.out.println("Query packet size:" + qpacket.size());
		Iterator it = qpacket.entrySet().iterator();
		while (it.hasNext()) {
			Map.Entry pairs = (Map.Entry)it.next();
			System.out.println(pairs.getKey() + " = " + pairs.getValue());
		}
		
		Apper apper = new Apper();
		return apper.processQuery(qpacket);
		
	}
  }

  public static void main(String [] args) {
  
	try {
		AppConfig aconfig = AppConfig.getInstance();
		aconfig.init(args);
	  	System.out.println("App Config Initialized");
	 	AppServerHandler handler = new AppServerHandler();
		AppServer.Processor processor = new AppServer.Processor(handler);
		TServerTransport serverTransport = new TServerSocket(9090);
		//TServer server = new TSimpleServer(processor, serverTransport);

		// Use this for a multithreaded server
		TServer server = new TThreadPoolServer(processor, serverTransport);

		System.out.println("Starting the server...");
		server.serve();

	} catch (Exception x) {
    	x.printStackTrace();
    }
    System.out.println("done.");
  }
}
