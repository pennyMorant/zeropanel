  reject:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/reject.txt"
    path: ./ruleset/reject.yaml
    interval: 86400

  icloud:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/icloud.txt"
    path: ./ruleset/icloud.yaml
    interval: 86400

  apple:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/apple.txt"
    path: ./ruleset/apple.yaml
    interval: 86400

  direct:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/direct.txt"
    path: ./ruleset/direct.yaml
    interval: 86400
    
  proxy:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/proxy.txt"
    path: ./ruleset/proxy.yaml
    interval: 86400

  private:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/private.txt"
    path: ./ruleset/private.yaml
    interval: 86400

  telegramcidr:
    type: http
    behavior: ipcidr
    url: "{$subUrl}/rules/clash/telegramcidr.txt"
    path: ./ruleset/telegramcidr.yaml
    interval: 86400
    
  youtube:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/youtube.txt"
    path: ./ruleset/youtube.yaml
    interval: 86400
    
  netflix:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/netflix.txt"
    path: ./ruleset/netflix.yaml
    interval: 86400
    
  socialapp:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/socialapp.txt"
    path: ./ruleset/socialapp.yaml
    interval: 86400
    
  googler:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/googler.txt"
    path: ./ruleset/googler.yaml
    interval: 86400
    
  google:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/google.txt"
    path: ./ruleset/google.yaml
    interval: 86400
    
  music:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/music.txt"
    path: ./ruleset/music.yaml
    interval: 86400
    
  video:
    type: http
    behavior: domain
    url: "{$subUrl}/rules/clash/video.txt"
    path: ./ruleset/video.yaml
    interval: 86400

  cncidr:
    type: http
    behavior: ipcidr
    url: "{$subUrl}/rules/clash/cncidr.txt"
    path: ./ruleset/cncidr.yaml
    interval: 86400

  lancidr:
    type: http
    behavior: ipcidr
    url: "{$subUrl}/rules/clash/lancidr.txt"
    path: ./ruleset/lancidr.yaml
    interval: 86400

  applications:
    type: http
    behavior: classical
    url: "{$subUrl}/rules/clash/applications.txt"
    path: ./ruleset/applications.yaml
    interval: 86400