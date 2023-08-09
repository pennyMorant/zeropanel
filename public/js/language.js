i18next.use(i18nextBrowserLanguageDetector).init({
    detection: {
      order: ['querystring', 'cookie', 'localStorage', 'navigator', 'htmlTag', 'path', 'subdomain'],
      caches: ['cookie']
    },
    fallbackLng: 'en',
    debug: false,
    resources: {
        en: {
          translation: {
            'copy success': 'Copy Success',
            'monthly fee': 'Monthly Fee',
            'quarterly fee': 'Quarterly Fee',
            'semi annua fee': 'Semi-annua Fee',
            'annual fee': 'Annua Fee',
            'biennial fee': 'Biennial Fee',
            'onetime fee': 'Onetime Fee',
            'insufficient permissions': 'Insufficient permissions',
            'confirm importing subscription link': 'Confirm importing subscription link?',
            'the value is not a valid email address': 'The value is not a valid email address',
            'email address is required': 'Email address is required',
            'password is required': 'Password is required',
            'sorry looks like there are some errors detected, please try again': 'Sorry, looks like there are some errors detected, please try again.',
            'please enter valid password': 'Please enter valid password',
            'password confirmation is required': 'Password confirmation is required',
            'password and its confirm are not the same': 'Password and its confirm are not the same',
            'day': 'Day',
            'minute': 'Minute',
            'hour': 'Hour',
            'second': "Second",
            'language': "Language"
          }
        },
        zh: {
          translation: {
            'copy success': '复制成功',
            'monthly fee': '月付',
            'quarterly fee': '季付',
            'semi annua fee': '半年付',
            'annual fee': '年付',
            'biennial fee': '两年付',
            'onetime fee': '一次性付',
            'insufficient permissions': '权限不足',
            'confirm importing subscription link': '确定导入订阅链接？',
            'the value is not a valid email address': '该值不是有效的电子邮件地址',
            'email address is required': '电子邮件地址不能为空',
            'password is required': '密码不能为空',
            'sorry looks like there are some errors detected, please try again': '很抱歉，似乎检测到了一些错误，请重试。',
            'please enter valid password': '请输入有效的密码',
            'password confirmation is required': '确认密码不能为空',
            'password and its confirm are not the same': '密码和确认密码不匹配',
            'day': '天',
            'minute': '分钟',
            'hour': '小时',
            'second': "秒",
            'language': "语言"
          }
        }
      }
  });


var lngHtml = {
  en: '<span class="menu-title position-relative">Language<span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">English<img class="w-15px h-15px rounded-1 ms-2" src="/theme/zero/assets/media/flags/united-states.svg" alt="" /></span></span>',
  zh: '<span class="menu-title position-relative">语言<span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">中文(简体)<img class="w-15px h-15px rounded-1 ms-2" src="/theme/zero/assets/media/flags/china.svg" alt="" /></span></span>',
}
if (getCookie('i18next') == 'zh-CN') {
  var browserLanguage = lngHtml.zh;
} else if (getCookie('i18next') == 'en-US') {
  var browserLanguage = lngHtml.en;
} else {
  if (navigator.language == 'zh-CN') {
    var browserLanguage = lngHtml.zh;
  } else {
    var browserLanguage = lngHtml.en;
  }
}
document.getElementById('zero_user_current_language').innerHTML = browserLanguage;
function changeCurrentLanguage(lng) {
  document.cookie = "i18next=" + lng;
  location.reload();
}