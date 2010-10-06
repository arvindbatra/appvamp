#$JAVA_HOME/bin/keytool -v -genkeypair -alias signFiles -keystore appkeystore  -keypass pa55123 -dname "cn=appvamp"  -storepass pa55123
$JAVA_HOME/bin/jarsigner -verbose -keystore appkeystore -storepass pa55123 -keypass pa55123 -signedjar SignedApplet.jar build/jar/applets.jar signFiles
cp -f -v SignedApplet.jar ../../public/dist/
#keytool -export -keystore appkeystore -storepass pa551  -alias signFiles -file appvampCert.cer
#keytool -import -alias appvamp -file appvampCert.cer -keystore appkeystore -storepass pa551


