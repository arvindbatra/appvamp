

namespace java ThriftProtocol
namespace cpp ThriftProtocol
namespace perl ThriftProtocol
namespace php ThriftProtocol


typedef map<string,string> QueryPacket



service AppServer {

  string query(1:QueryPacket qpacket)


}
