PROCESS-NAME,v2ray,DIRECT
PROCESS-NAME,xray,DIRECT
PROCESS-NAME,clash,DIRECT
PROCESS-NAME,naive,DIRECT
PROCESS-NAME,trojan,DIRECT
PROCESS-NAME,trojan-go,DIRECT
PROCESS-NAME,ss-local,DIRECT
PROCESS-NAME,privoxy,DIRECT
PROCESS-NAME,leaf,DIRECT
PROCESS-NAME,Thunder,DIRECT
PROCESS-NAME,DownloadService,DIRECT
PROCESS-NAME,qBittorrent,DIRECT
PROCESS-NAME,Transmission,DIRECT
PROCESS-NAME,fdm,DIRECT
PROCESS-NAME,aria2c,DIRECT
PROCESS-NAME,Folx,DIRECT
PROCESS-NAME,NetTransport,DIRECT
PROCESS-NAME,uTorrent,DIRECT
PROCESS-NAME,WebTorrent,DIRECT
URL-REGEX,(Subject|HELO|SMTP),DIRECT
URL-REGEX,(api|ps|sv|offnavi|newvector|ulog.imap|newloc)(.map|).(baidu|n.shifen).com,DIRECT
URL-REGEX,(.+.|^)(360|so|qihoo|360safe|qhimg|360totalsecurity|yunpan).(cn|com),DIRECT
URL-REGEX,(.+.)?(torrent|announce.php?passkey=|tracker|BitTorrent|bt_key|ed2k|find_node|get_peers|info_hash|magnet:|peer_id=|xunlei)(..+)?,DIRECT
URL-REGEX,(.?)(xunlei|sandai|Thunder|XLLiveUD)(.),DIRECT
PROCESS-NAME,DownloadService,DIRECT
URL-REGEX,(.+\.|^)(360|so)\.(cn|com),DIRECT
PROCESS-NAME,Weiyun,DIRECT

DOMAIN-SET,{$config['baseUrl']}/rules/surge/private.txt,DIRECT
DOMAIN-SET,{$config['baseUrl']}/rules/surge/reject.txt,REJECT
RULE-SET,SYSTEM,DIRECT
DOMAIN-SET,{$config['baseUrl']}/rules/surge/icloud.txt,🍎 APPLE
DOMAIN-SET,{$config['baseUrl']}/rules/surge/apple.txt,🍎 APPLE
RULE-SET,{$config['baseUrl']}/rules/surge/telegramcidr.txt,✈️ TELEGRAM
RULE-SET,{$config['baseUrl']}/rules/surge/cncidr.txt,DIRECT
DOMAIN-SET,{$config['baseUrl']}/rules/surge/netflix.txt,🎞 NETFLIX
DOMAIN-SET,{$config['baseUrl']}/rules/surge/music.txt,🎧 MUSIC
DOMAIN-SET,{$config['baseUrl']}/rules/surge/video.txt,📺 VIDEO
DOMAIN-SET,{$config['baseUrl']}/rules/surge/socialapp.txt,🪁 SOCIAL APP
DOMAIN-SET,{$config['baseUrl']}/rules/surge/google.txt,🔍 GOOGLE
DOMAIN-SET,{$config['baseUrl']}/rules/surge/googler.txt,🔍 GOOGLE
DOMAIN-SET,{$config['baseUrl']}/rules/surge/youtube.txt,🎬 YOUTUBE
DOMAIN-SET,{$config['baseUrl']}/rules/surge/proxy.txt,🌎 PROXY,force-remote-dns
DOMAIN-SET,{$config['baseUrl']}/rules/surge/direct.txt,DIRECT
RULE-SET,LAN,DIRECT
FINAL,🌎 PROXY,dns-failed