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
            'insufficient permissions': 'Insufficient permissions',
            'confirm importing subscription link': 'Confirm importing subscription link?',
            'the value is not a valid email address': 'The value is not a valid email address',
            'email address is required': 'Email address is required',
            'password is required': 'Password is required',
            'sorry looks like there are some errors detected, please try again': 'Sorry, looks like there are some errors detected, please try again.',
            'please enter valid password': 'Please enter valid password',
            'password confirmation is required': 'Password confirmation is required',
            'password and its confirm are not the same': 'Password and its confirm are not the same',
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
            'insufficient permissions': '权限不足',
            'confirm importing subscription link': '确定导入订阅链接？',
            'the value is not a valid email address': '该值不是有效的电子邮件地址',
            'email address is required': '电子邮件地址不能为空',
            'password is required': '密码不能为空',
            'sorry looks like there are some errors detected, please try again': '很抱歉，似乎检测到了一些错误，请重试。',
            'please enter valid password': '请输入有效的密码',
            'password confirmation is required': '确认密码不能为空',
            'password and its confirm are not the same': '密码和确认密码不匹配',
          }
        }
      }
  });