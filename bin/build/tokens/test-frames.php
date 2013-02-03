<?xml version="1.0" encoding="UTF-8" ?>
<tokens>
	<symbol id="0" text="#" range="0:0:0-1:0:1" />
	<symbol id="1" text="!" range="1:0:1-2:0:2" />
	<symbol id="2" text="/" range="2:0:2-3:0:3" />
	<identifier id="3" text="usr" range="3:0:3-6:0:6" />
	<symbol id="4" text="/" range="6:0:6-7:0:7" />
	<identifier id="5" text="bin" range="7:0:7-10:0:10" />
	<symbol id="6" text="/" range="10:0:10-11:0:11" />
	<identifier id="7" text="php" range="11:0:11-14:0:14" />
	<whitespace id="8" text="&#10;" range="14:0:14-15:1:0" />
	<symbol id="9" text="&lt;" range="15:1:0-16:1:1" />
	<symbol id="10" text="?" range="16:1:1-17:1:2" />
	<identifier id="11" text="php" range="17:1:2-20:1:5" />
	<whitespace id="12" text="&#10;&#10;" range="20:1:5-22:3:0" />
	<comment id="13" text="/**&#10; * This script acts as a debugging server that accepts random communication&#10; * frames and returns a response to them.&#10; */" range="22:3:0-147:6:3" />
	<whitespace id="14" text="&#10;&#10;" range="147:6:3-149:8:0" />
	<identifier id="15" text="require_once" range="149:8:0-161:8:12" />
	<whitespace id="16" text=" " range="161:8:12-162:8:13" />
	<identifier id="17" text="__DIR__" range="162:8:13-169:8:20" />
	<symbol id="18" text="." range="169:8:20-170:8:21" />
	<string id="19" text="/../source/autoload.php" range="170:8:21-195:8:46" />
	<symbol id="20" text=";" range="195:8:46-196:8:47" />
	<whitespace id="21" text="&#10;&#10;" range="196:8:47-198:10:0" />
	<comment id="22" text="//Initialize the socket.&#10;" range="198:10:0-223:11:0" />
	<symbol id="23" text="$" range="223:11:0-224:11:1" />
	<identifier id="24" text="socketPath" range="224:11:1-234:11:11" />
	<whitespace id="25" text=" " range="234:11:11-235:11:12" />
	<symbol id="26" text="=" range="235:11:12-236:11:13" />
	<whitespace id="27" text=" " range="236:11:13-237:11:14" />
	<string id="28" text="/tmp/quantum-frames.sock" range="237:11:14-263:11:40" />
	<symbol id="29" text=";" range="263:11:40-264:11:41" />
	<whitespace id="30" text="&#10;" range="264:11:41-265:12:0" />
	<identifier id="31" text="@unlink" range="265:12:0-272:12:7" />
	<group id="358" text="()" range="272:12:7-285:12:20">
		<symbol id="33" text="$" range="273:12:8-274:12:9" />
		<identifier id="34" text="socketPath" range="274:12:9-284:12:19" />
	</group>
	<symbol id="36" text=";" range="285:12:20-286:12:21" />
	<whitespace id="37" text="&#10;" range="286:12:21-287:13:0" />
	<symbol id="38" text="$" range="287:13:0-288:13:1" />
	<identifier id="39" text="socket" range="288:13:1-294:13:7" />
	<whitespace id="40" text=" " range="294:13:7-295:13:8" />
	<symbol id="41" text="=" range="295:13:8-296:13:9" />
	<whitespace id="42" text=" " range="296:13:9-297:13:10" />
	<identifier id="43" text="socket_create" range="297:13:10-310:13:23" />
	<group id="359" text="()" range="310:13:23-335:13:48">
		<identifier id="45" text="AF_UNIX" range="311:13:24-318:13:31" />
		<symbol id="46" text="," range="318:13:31-319:13:32" />
		<whitespace id="47" text=" " range="319:13:32-320:13:33" />
		<identifier id="48" text="SOCK_STREAM" range="320:13:33-331:13:44" />
		<symbol id="49" text="," range="331:13:44-332:13:45" />
		<whitespace id="50" text=" " range="332:13:45-333:13:46" />
		<number id="51" text="0" range="333:13:46-334:13:47" />
	</group>
	<symbol id="53" text=";" range="335:13:48-336:13:49" />
	<whitespace id="54" text="&#10;" range="336:13:49-337:14:0" />
	<identifier id="55" text="if" range="337:14:0-339:14:2" />
	<whitespace id="56" text=" " range="339:14:2-340:14:3" />
	<group id="360" text="()" range="340:14:3-350:14:13">
		<symbol id="58" text="!" range="341:14:4-342:14:5" />
		<symbol id="59" text="$" range="342:14:5-343:14:6" />
		<identifier id="60" text="socket" range="343:14:6-349:14:12" />
	</group>
	<whitespace id="62" text=" " range="350:14:13-351:14:14" />
	<group id="361" text="{}" range="351:14:14-412:16:1">
		<whitespace id="64" text="&#10;&#9;" range="352:14:15-354:15:1" />
		<identifier id="65" text="throw" range="354:15:1-359:15:6" />
		<whitespace id="66" text=" " range="359:15:6-360:15:7" />
		<identifier id="67" text="new" range="360:15:7-363:15:10" />
		<whitespace id="68" text=" " range="363:15:10-364:15:11" />
		<symbol id="69" text="\" range="364:15:11-365:15:12" />
		<identifier id="70" text="RuntimeException" range="365:15:12-381:15:28" />
		<group id="362" text="()" range="381:15:28-409:15:56">
			<string id="72" text="Unable to create socket." range="382:15:29-408:15:55" />
		</group>
		<symbol id="74" text=";" range="409:15:56-410:15:57" />
		<whitespace id="75" text="&#10;" range="410:15:57-411:16:0" />
	</group>
	<whitespace id="77" text="&#10;" range="412:16:1-413:17:0" />
	<identifier id="78" text="if" range="413:17:0-415:17:2" />
	<whitespace id="79" text=" " range="415:17:2-416:17:3" />
	<group id="363" text="()" range="416:17:3-452:17:39">
		<symbol id="81" text="!" range="417:17:4-418:17:5" />
		<identifier id="82" text="socket_bind" range="418:17:5-429:17:16" />
		<group id="364" text="()" range="429:17:16-451:17:38">
			<symbol id="84" text="$" range="430:17:17-431:17:18" />
			<identifier id="85" text="socket" range="431:17:18-437:17:24" />
			<symbol id="86" text="," range="437:17:24-438:17:25" />
			<whitespace id="87" text=" " range="438:17:25-439:17:26" />
			<symbol id="88" text="$" range="439:17:26-440:17:27" />
			<identifier id="89" text="socketPath" range="440:17:27-450:17:37" />
		</group>
	</group>
	<whitespace id="92" text=" " range="452:17:39-453:17:40" />
	<group id="365" text="{}" range="453:17:40-520:19:1">
		<whitespace id="94" text="&#10;&#9;" range="454:17:41-456:18:1" />
		<identifier id="95" text="throw" range="456:18:1-461:18:6" />
		<whitespace id="96" text=" " range="461:18:6-462:18:7" />
		<identifier id="97" text="new" range="462:18:7-465:18:10" />
		<whitespace id="98" text=" " range="465:18:10-466:18:11" />
		<symbol id="99" text="\" range="466:18:11-467:18:12" />
		<identifier id="100" text="RuntimeException" range="467:18:12-483:18:28" />
		<group id="366" text="()" range="483:18:28-517:18:62">
			<string id="102" text="Unable to bind to $socketPath." range="484:18:29-516:18:61" />
		</group>
		<symbol id="104" text=";" range="517:18:62-518:18:63" />
		<whitespace id="105" text="&#10;" range="518:18:63-519:19:0" />
	</group>
	<whitespace id="107" text="&#10;" range="520:19:1-521:20:0" />
	<identifier id="108" text="socket_listen" range="521:20:0-534:20:13" />
	<group id="367" text="()" range="534:20:13-543:20:22">
		<symbol id="110" text="$" range="535:20:14-536:20:15" />
		<identifier id="111" text="socket" range="536:20:15-542:20:21" />
	</group>
	<symbol id="113" text=";" range="543:20:22-544:20:23" />
	<whitespace id="114" text="&#10;&#10;" range="544:20:23-546:22:0" />
	<comment id="115" text="//Accept connections and perform the frame communication.&#10;" range="546:22:0-604:23:0" />
	<identifier id="116" text="while" range="604:23:0-609:23:5" />
	<whitespace id="117" text=" " range="609:23:5-610:23:6" />
	<group id="368" text="()" range="610:23:6-616:23:12">
		<identifier id="119" text="true" range="611:23:7-615:23:11" />
	</group>
	<whitespace id="121" text=" " range="616:23:12-617:23:13" />
	<group id="369" text="{}" range="617:23:13-1420:53:1">
		<whitespace id="123" text="&#10;&#9;" range="618:23:14-620:24:1" />
		<symbol id="124" text="$" range="620:24:1-621:24:2" />
		<identifier id="125" text="client" range="621:24:2-627:24:8" />
		<whitespace id="126" text=" " range="627:24:8-628:24:9" />
		<symbol id="127" text="=" range="628:24:9-629:24:10" />
		<whitespace id="128" text=" " range="629:24:10-630:24:11" />
		<identifier id="129" text="socket_accept" range="630:24:11-643:24:24" />
		<group id="370" text="()" range="643:24:24-652:24:33">
			<symbol id="131" text="$" range="644:24:25-645:24:26" />
			<identifier id="132" text="socket" range="645:24:26-651:24:32" />
		</group>
		<symbol id="134" text=";" range="652:24:33-653:24:34" />
		<whitespace id="135" text="&#10;&#9;" range="653:24:34-655:25:1" />
		<identifier id="136" text="echo" range="655:25:1-659:25:5" />
		<whitespace id="137" text=" " range="659:25:5-660:25:6" />
		<string id="138" text="Client connected\n" range="660:25:6-680:25:26" />
		<symbol id="139" text=";" range="680:25:26-681:25:27" />
		<whitespace id="140" text="&#10;&#10;&#9;" range="681:25:27-684:27:1" />
		<comment id="141" text="/*/" range="684:27:1-687:27:4" />
		<symbol id="142" text="/" range="687:27:4-688:27:5" />
		<identifier id="143" text="Read" range="688:27:5-692:27:9" />
		<whitespace id="144" text=" " range="692:27:9-693:27:10" />
		<identifier id="145" text="the" range="693:27:10-696:27:13" />
		<whitespace id="146" text=" " range="696:27:13-697:27:14" />
		<identifier id="147" text="header" range="697:27:14-703:27:20" />
		<symbol id="148" text="." range="703:27:20-704:27:21" />
		<whitespace id="149" text="&#10;&#9;" range="704:27:21-706:28:1" />
		<symbol id="150" text="$" range="706:28:1-707:28:2" />
		<identifier id="151" text="header" range="707:28:2-713:28:8" />
		<whitespace id="152" text=" " range="713:28:8-714:28:9" />
		<symbol id="153" text="=" range="714:28:9-715:28:10" />
		<whitespace id="154" text=" " range="715:28:10-716:28:11" />
		<identifier id="155" text="socket_read" range="716:28:11-727:28:22" />
		<group id="371" text="()" range="727:28:22-739:28:34">
			<symbol id="157" text="$" range="728:28:23-729:28:24" />
			<identifier id="158" text="client" range="729:28:24-735:28:30" />
			<symbol id="159" text="," range="735:28:30-736:28:31" />
			<whitespace id="160" text=" " range="736:28:31-737:28:32" />
			<number id="161" text="5" range="737:28:32-738:28:33" />
		</group>
		<symbol id="163" text=";" range="739:28:34-740:28:35" />
		<whitespace id="164" text="&#10;&#9;" range="740:28:35-742:29:1" />
		<symbol id="165" text="$" range="742:29:1-743:29:2" />
		<identifier id="166" text="header" range="743:29:2-749:29:8" />
		<whitespace id="167" text=" " range="749:29:8-750:29:9" />
		<symbol id="168" text="=" range="750:29:9-751:29:10" />
		<whitespace id="169" text=" " range="751:29:10-752:29:11" />
		<identifier id="170" text="unpack" range="752:29:11-758:29:17" />
		<group id="372" text="()" range="758:29:17-784:29:43">
			<string id="172" text="Ctype/Llength" range="759:29:18-774:29:33" />
			<symbol id="173" text="," range="774:29:33-775:29:34" />
			<whitespace id="174" text=" " range="775:29:34-776:29:35" />
			<symbol id="175" text="$" range="776:29:35-777:29:36" />
			<identifier id="176" text="header" range="777:29:36-783:29:42" />
		</group>
		<symbol id="178" text=";" range="784:29:43-785:29:44" />
		<whitespace id="179" text="&#10;&#9;" range="785:29:44-787:30:1" />
		<symbol id="180" text="$" range="787:30:1-788:30:2" />
		<identifier id="181" text="type" range="788:30:2-792:30:6" />
		<whitespace id="182" text=" " range="792:30:6-793:30:7" />
		<symbol id="183" text="=" range="793:30:7-794:30:8" />
		<whitespace id="184" text=" " range="794:30:8-795:30:9" />
		<symbol id="185" text="$" range="795:30:9-796:30:10" />
		<identifier id="186" text="header" range="796:30:10-802:30:16" />
		<group id="373" text="[]" range="802:30:16-810:30:24">
			<string id="188" text="type" range="803:30:17-809:30:23" />
		</group>
		<symbol id="190" text=";" range="810:30:24-811:30:25" />
		<whitespace id="191" text="&#10;&#9;" range="811:30:25-813:31:1" />
		<symbol id="192" text="$" range="813:31:1-814:31:2" />
		<identifier id="193" text="length" range="814:31:2-820:31:8" />
		<whitespace id="194" text=" " range="820:31:8-821:31:9" />
		<symbol id="195" text="=" range="821:31:9-822:31:10" />
		<whitespace id="196" text=" " range="822:31:10-823:31:11" />
		<symbol id="197" text="$" range="823:31:11-824:31:12" />
		<identifier id="198" text="header" range="824:31:12-830:31:18" />
		<group id="374" text="[]" range="830:31:18-840:31:28">
			<string id="200" text="length" range="831:31:19-839:31:27" />
		</group>
		<symbol id="202" text=";" range="840:31:28-841:31:29" />
		<whitespace id="203" text="&#10;&#9;" range="841:31:29-843:32:1" />
		<identifier id="204" text="echo" range="843:32:1-847:32:5" />
		<whitespace id="205" text=" " range="847:32:5-848:32:6" />
		<string id="206" text="Expecting $length Bytes of data type $type\n" range="848:32:6-894:32:52" />
		<symbol id="207" text=";" range="894:32:52-895:32:53" />
		<whitespace id="208" text="&#10;&#10;&#9;" range="895:32:53-898:34:1" />
		<comment id="209" text="//Read the data.&#10;" range="898:34:1-915:35:0" />
		<whitespace id="210" text="&#9;" range="915:35:0-916:35:1" />
		<symbol id="211" text="$" range="916:35:1-917:35:2" />
		<identifier id="212" text="data" range="917:35:2-921:35:6" />
		<whitespace id="213" text=" " range="921:35:6-922:35:7" />
		<symbol id="214" text="=" range="922:35:7-923:35:8" />
		<whitespace id="215" text=" " range="923:35:8-924:35:9" />
		<identifier id="216" text="socket_read" range="924:35:9-935:35:20" />
		<group id="375" text="()" range="935:35:20-953:35:38">
			<symbol id="218" text="$" range="936:35:21-937:35:22" />
			<identifier id="219" text="client" range="937:35:22-943:35:28" />
			<symbol id="220" text="," range="943:35:28-944:35:29" />
			<whitespace id="221" text=" " range="944:35:29-945:35:30" />
			<symbol id="222" text="$" range="945:35:30-946:35:31" />
			<identifier id="223" text="length" range="946:35:31-952:35:37" />
		</group>
		<symbol id="225" text=";" range="953:35:38-954:35:39" />
		<whitespace id="226" text="&#10;&#9;" range="954:35:39-956:36:1" />
		<identifier id="227" text="echo" range="956:36:1-960:36:5" />
		<whitespace id="228" text=" " range="960:36:5-961:36:6" />
		<string id="229" text="Received \&quot;$data\&quot;\n" range="961:36:6-983:36:28" />
		<symbol id="230" text=";" range="983:36:28-984:36:29" />
		<symbol id="231" text="*" range="984:36:29-985:36:30" />
		<symbol id="232" text="/" range="985:36:30-986:36:31" />
		<whitespace id="233" text="&#10;&#10;&#9;" range="986:36:31-989:38:1" />
		<comment id="234" text="//Create a new FrameSocket for this client.&#10;" range="989:38:1-1033:39:0" />
		<whitespace id="235" text="&#9;" range="1033:39:0-1034:39:1" />
		<symbol id="236" text="$" range="1034:39:1-1035:39:2" />
		<identifier id="237" text="done" range="1035:39:2-1039:39:6" />
		<whitespace id="238" text=" " range="1039:39:6-1040:39:7" />
		<symbol id="239" text="=" range="1040:39:7-1041:39:8" />
		<whitespace id="240" text=" " range="1041:39:8-1042:39:9" />
		<identifier id="241" text="false" range="1042:39:9-1047:39:14" />
		<symbol id="242" text=";" range="1047:39:14-1048:39:15" />
		<whitespace id="243" text="&#10;&#9;" range="1048:39:15-1050:40:1" />
		<symbol id="244" text="$" range="1050:40:1-1051:40:2" />
		<identifier id="245" text="fs" range="1051:40:2-1053:40:4" />
		<whitespace id="246" text=" " range="1053:40:4-1054:40:5" />
		<symbol id="247" text="=" range="1054:40:5-1055:40:6" />
		<whitespace id="248" text=" " range="1055:40:6-1056:40:7" />
		<identifier id="249" text="new" range="1056:40:7-1059:40:10" />
		<whitespace id="250" text=" " range="1059:40:10-1060:40:11" />
		<identifier id="251" text="FrameSocket" range="1060:40:11-1071:40:22" />
		<whitespace id="252" text=" " range="1071:40:22-1072:40:23" />
		<group id="376" text="()" range="1072:40:23-1311:45:3">
			<symbol id="254" text="$" range="1073:40:24-1074:40:25" />
			<identifier id="255" text="client" range="1074:40:25-1080:40:31" />
			<symbol id="256" text="," range="1080:40:31-1081:40:32" />
			<whitespace id="257" text=" " range="1081:40:32-1082:40:33" />
			<identifier id="258" text="function" range="1082:40:33-1090:40:41" />
			<group id="377" text="()" range="1090:40:41-1104:40:55">
				<identifier id="260" text="Frame" range="1091:40:42-1096:40:47" />
				<whitespace id="261" text=" " range="1096:40:47-1097:40:48" />
				<symbol id="262" text="$" range="1097:40:48-1098:40:49" />
				<identifier id="263" text="frame" range="1098:40:49-1103:40:54" />
			</group>
			<whitespace id="265" text=" " range="1104:40:55-1105:40:56" />
			<identifier id="266" text="use" range="1105:40:56-1108:40:59" />
			<whitespace id="267" text=" " range="1108:40:59-1109:40:60" />
			<group id="378" text="()" range="1109:40:60-1117:40:68">
				<symbol id="269" text="&amp;" range="1110:40:61-1111:40:62" />
				<symbol id="270" text="$" range="1111:40:62-1112:40:63" />
				<identifier id="271" text="done" range="1112:40:63-1116:40:67" />
			</group>
			<whitespace id="273" text=" " range="1117:40:68-1118:40:69" />
			<group id="379" text="{}" range="1118:40:69-1310:45:2">
				<whitespace id="275" text="&#10;&#9;&#9;" range="1119:40:70-1122:41:2" />
				<identifier id="276" text="echo" range="1122:41:2-1126:41:6" />
				<whitespace id="277" text=" " range="1126:41:6-1127:41:7" />
				<string id="278" text="Received frame of type {$frame-&gt;getType()}\n" range="1127:41:7-1173:41:53" />
				<symbol id="279" text=";" range="1173:41:53-1174:41:54" />
				<whitespace id="280" text="&#10;&#9;&#9;" range="1174:41:54-1177:42:2" />
				<symbol id="281" text="$" range="1177:42:2-1178:42:3" />
				<identifier id="282" text="f" range="1178:42:3-1179:42:4" />
				<whitespace id="283" text=" " range="1179:42:4-1180:42:5" />
				<symbol id="284" text="=" range="1180:42:5-1181:42:6" />
				<whitespace id="285" text=" " range="1181:42:6-1182:42:7" />
				<identifier id="286" text="Frame" range="1182:42:7-1187:42:12" />
				<symbol id="287" text=":" range="1187:42:12-1188:42:13" />
				<symbol id="288" text=":" range="1188:42:13-1189:42:14" />
				<identifier id="289" text="unserialize" range="1189:42:14-1200:42:25" />
				<group id="380" text="()" range="1200:42:25-1219:42:44">
					<symbol id="291" text="$" range="1201:42:26-1202:42:27" />
					<identifier id="292" text="frame" range="1202:42:27-1207:42:32" />
					<symbol id="293" text="-&gt;" range="1207:42:32-1209:42:34" />
					<identifier id="294" text="getData" range="1209:42:34-1216:42:41" />
					<group id="381" text="()" range="1216:42:41-1218:42:43" />
				</group>
				<symbol id="298" text=";" range="1219:42:44-1220:42:45" />
				<whitespace id="299" text="&#10;&#9;&#9;" range="1220:42:45-1223:43:2" />
				<identifier id="300" text="echo" range="1223:43:2-1227:43:6" />
				<whitespace id="301" text=" " range="1227:43:6-1228:43:7" />
				<string id="302" text="-&gt; contains frame of type {$f-&gt;getType()}: {$f-&gt;getData()}\n" range="1228:43:7-1290:43:69" />
				<symbol id="303" text=";" range="1290:43:69-1291:43:70" />
				<whitespace id="304" text="&#10;&#9;&#9;" range="1291:43:70-1294:44:2" />
				<symbol id="305" text="$" range="1294:44:2-1295:44:3" />
				<identifier id="306" text="done" range="1295:44:3-1299:44:7" />
				<whitespace id="307" text=" " range="1299:44:7-1300:44:8" />
				<symbol id="308" text="=" range="1300:44:8-1301:44:9" />
				<whitespace id="309" text=" " range="1301:44:9-1302:44:10" />
				<identifier id="310" text="true" range="1302:44:10-1306:44:14" />
				<symbol id="311" text=";" range="1306:44:14-1307:44:15" />
				<whitespace id="312" text="&#10;&#9;" range="1307:44:15-1309:45:1" />
			</group>
		</group>
		<symbol id="315" text=";" range="1311:45:3-1312:45:4" />
		<whitespace id="316" text="&#10;&#10;&#9;" range="1312:45:4-1315:47:1" />
		<identifier id="317" text="while" range="1315:47:1-1320:47:6" />
		<whitespace id="318" text=" " range="1320:47:6-1321:47:7" />
		<group id="382" text="()" range="1321:47:7-1329:47:15">
			<symbol id="320" text="!" range="1322:47:8-1323:47:9" />
			<symbol id="321" text="$" range="1323:47:9-1324:47:10" />
			<identifier id="322" text="done" range="1324:47:10-1328:47:14" />
		</group>
		<whitespace id="324" text=" " range="1329:47:15-1330:47:16" />
		<group id="383" text="{}" range="1330:47:16-1393:50:2">
			<whitespace id="326" text="&#10;&#9;&#9;" range="1331:47:17-1334:48:2" />
			<identifier id="327" text="echo" range="1334:48:2-1338:48:6" />
			<whitespace id="328" text=" " range="1338:48:6-1339:48:7" />
			<string id="329" text="Waiting for data...\n" range="1339:48:7-1362:48:30" />
			<symbol id="330" text=";" range="1362:48:30-1363:48:31" />
			<whitespace id="331" text="&#10;&#9;&#9;" range="1363:48:31-1366:49:2" />
			<identifier id="332" text="if" range="1366:49:2-1368:49:4" />
			<whitespace id="333" text=" " range="1368:49:4-1369:49:5" />
			<group id="384" text="()" range="1369:49:5-1383:49:19">
				<symbol id="335" text="!" range="1370:49:6-1371:49:7" />
				<symbol id="336" text="$" range="1371:49:7-1372:49:8" />
				<identifier id="337" text="fs" range="1372:49:8-1374:49:10" />
				<symbol id="338" text="-&gt;" range="1374:49:10-1376:49:12" />
				<identifier id="339" text="read" range="1376:49:12-1380:49:16" />
				<group id="385" text="()" range="1380:49:16-1382:49:18" />
			</group>
			<whitespace id="343" text=" " range="1383:49:19-1384:49:20" />
			<identifier id="344" text="break" range="1384:49:20-1389:49:25" />
			<symbol id="345" text=";" range="1389:49:25-1390:49:26" />
			<whitespace id="346" text="&#10;&#9;" range="1390:49:26-1392:50:1" />
		</group>
		<whitespace id="348" text="&#10;&#10;&#9;" range="1393:50:2-1396:52:1" />
		<identifier id="349" text="socket_close" range="1396:52:1-1408:52:13" />
		<group id="386" text="()" range="1408:52:13-1417:52:22">
			<symbol id="351" text="$" range="1409:52:14-1410:52:15" />
			<identifier id="352" text="client" range="1410:52:15-1416:52:21" />
		</group>
		<symbol id="354" text=";" range="1417:52:22-1418:52:23" />
		<whitespace id="355" text="&#10;" range="1418:52:23-1419:53:0" />
	</group>
	<whitespace id="357" text="&#10;" range="1420:53:1-1421:54:0" />
</tokens>
