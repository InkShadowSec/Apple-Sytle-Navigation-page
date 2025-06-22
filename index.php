<?php
// 配置文件 - 存储网站分组和链接
$siteGroups = [
    '常用网站' => [
        ['name' => '今日热榜', 'url' => 'https://tophub.today/', 'ico' => 'ico/top.ico'],
        ['name' => '百度', 'url' => 'https://www.baidu.com', 'ico' => 'ico/baidu.ico'],
        ['name' => 'B站', 'url' => 'https://www.bilibili.com/', 'ico' => 'ico/bilibili.ico'],
        ['name' => '抖音', 'url' => 'https://www.douyin.com/', 'ico' => 'ico/douyin.ico'],
        ['name' => '语雀', 'url' => 'https://www.yuque.com/dashboard', 'ico' => 'ico/yuque.ico'],
        ['name' => 'GitHub', 'url' => 'https://github.com', 'ico' => 'ico/github.ico'],
        ['name' => '微信公众平台', 'url' => 'https://mp.weixin.qq.com', 'ico' => 'ico/wx.ico'],
        ['name' => 'DeepSeek', 'url' => 'https://chat.deepseek.com/', 'ico' => 'ico/deepseek.ico'],
        ['name' => '豆包', 'url' => 'https://www.doubao.com/', 'ico' => 'ico/doubao.ico'],
        ['name' => 'QQ邮箱', 'url' => 'https://mail.qq.com/', 'ico' => 'ico/qqmail.ico'],
        ['name' => '吾爱破解', 'url' => 'https://www.52pojie.cn/', 'ico' => 'ico/52pojie.ico'],
        ['name' => '博树', 'url' => 'https://www.busuu.com/', 'ico' => 'ico/busuu.ico']
    ],
    '安全靶场' => [
        ['name' => 'BP靶场', 'url' => 'https://portswigger.net/web-security/all-labs', 'ico' => 'ico/bp.ico'],
        ['name' => '玄机靶场', 'url' => 'https://xj.edisec.net/challenges', 'ico' => 'ico/xuanji.ico'],
        ['name' => 'MHT靶场', 'url' => 'https://vip.bdziyi.com/bc/', 'ico' => 'ico/mht.ico'],
    ]
];

// 获取用户 IP 地址
$login_ip = isset($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
$login_ip = ($login_ip) ? $login_ip : $_SERVER["REMOTE_ADDR"];

function get_ip_city($ip)
{
    $ch = curl_init();
    $url = 'https://whois.pconline.com.cn/ipJson.jsp?ip=' . $ip;
    //用curl发送接收数据
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //请求为https
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $location = curl_exec($ch);
    curl_close($ch);
    //转码
    $location = mb_convert_encoding($location, 'utf-8', 'GB2312');
    //截取{}中的字符串
    $location = substr($location, strlen('({') + strpos($location, '({'), (strlen($location) - strpos($location, '})')) * (-1));
    //将截取的字符串$location中的‘，’替换成‘&’   将字符串中的‘：‘替换成‘=’
    $location = str_replace('"', "", str_replace(":", "=", str_replace(",", "&", $location)));
    //php内置函数，将处理成类似于url参数的格式的字符串  转换成数组
    parse_str($location, $ip_location);
    return $ip_location; # 返回数组
}

// 获取 IP 归属地
$ip_location = get_ip_city($login_ip);
$ip_address = $login_ip;
$ip_addr = isset($ip_location['addr']) ? $ip_location['addr'] : '未知位置';

// 获取天气信息 到http://tianqiapi.com/注册一下就可以免费使用
function get_weather($ip) {
    $appid = 'yourappid';
    $appsecret = 'yourappsecret';
    $version = 'v61';
    $unescape = 1;
    $url = "http://gfeljm.tianqiapi.com/api?unescape={$unescape}&version={$version}&appid={$appid}&appsecret={$appsecret}&ip={$ip}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $weather_data = curl_exec($ch);
    curl_close($ch);

    return json_decode($weather_data, true);
}

$weather = get_weather($login_ip);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>墨影Sec导航页</title>
    <link rel="shortcut icon" href="title.ico" type="image/x-icon">
    <!-- 加载 Tailwind CSS 并开启 CDN 缓存 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- 加载 Font Awesome 图标库并开启 CDN 缓存 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        :root {
            /* 液态玻璃效果变量 */
            --shadow-offset: 0;
            --shadow-blur: 20px;
            --shadow-spread: -5px;
            --shadow-color: rgba(255, 255, 255, 0.7);
            --tint-color: 255, 255, 255;
            --tint-opacity: 0.4;
            --frost-blur: 4px;
            --noise-frequency: 0.008;
            --distortion-strength: 77;
            --outer-shadow-blur: 24px;
        }
        
        /* 液态玻璃卡片基础样式 */
        .liquid-glass-card {
            position: relative;
            isolation: isolate;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .liquid-glass-card::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: 0;
            border-radius: inherit;
            box-shadow: inset var(--shadow-offset) var(--shadow-offset) var(--shadow-blur) var(--shadow-spread) var(--shadow-color);
            background-color: rgba(var(--tint-color), var(--tint-opacity));
        }
        
        .liquid-glass-card::after {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -1;
            border-radius: inherit;
            backdrop-filter: blur(var(--frost-blur));
            filter: url(#glass-distortion);
            isolation: isolate;
            -webkit-backdrop-filter: blur(var(--frost-blur));
        }
        
        /* 背景动画 */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('back.png') no-repeat center center fixed;
            z-index: -2;
            animation: backgroundShift 30s infinite alternate;
            background-size: cover;
        }
        
        @keyframes backgroundShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(245,245,247,0.7) 0%, rgba(210,230,255,0.5) 100%);
            z-index: -1;
        }
        
        /* 卡片悬停动画 */
        .liquid-glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.3);
        }
        
        /* 图标容器 */
        .icon-container {
            width:  32px;
            height: 32px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }
        
        .liquid-glass-card:hover .icon-container {
            transform: scale(1.1);
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* 自定义滚动条 */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.4);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.6);
        }
        
        /* 天气信息动画 */
        .weather-info {
            transition: all 0.5s ease;
        }
        
        .weather-info:hover {
            transform: translateY(-3px);
        }
        
        /* 新增：页面整体居中 */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
            color: black; /* 将文字颜色改为黑色 */
        }
        
        /* 搜索框样式 */
        .search-container {
            max-width: 600px;
            width: 100%;
        }
        
        .search-input {
            background: rgba(16, 164, 194, 0.3);
            border: none;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            background: rgba(16, 164, 194, 0.3);
            outline: none;
        }
        
        /* 网站卡片样式优化 */
        .site-card {
            position: relative;
            isolation: isolate;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .site-card::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: 0;
            border-radius: inherit;
            box-shadow: inset var(--shadow-offset) var(--shadow-offset) var(--shadow-blur) var(--shadow-spread) var(--shadow-color);
            background-color: rgba(var(--tint-color), var(--tint-opacity));
        }
        
        .site-card::after {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -1;
            border-radius: inherit;
            backdrop-filter: blur(var(--frost-blur));
            filter: url(#glass-distortion);
            isolation: isolate;
            -webkit-backdrop-filter: blur(var(--frost-blur));
        }
        
        .site-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.3);
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0071e3', // 苹果蓝
                        secondary: '#6e6e73',
                        accent: '#ff3b30',
                        dark: '#1d1d1f',
                        light: '#f5f5f7',
                        'apple-gray': '#86868b',
                        'apple-light': '#fbfbfd',
                    },
                    fontFamily: {
                        sans: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                    backdropBlur: {
                        'lg': '16px',
                    },
                }
            }
        }
    </script>
</head>
<body class="min-h-screen font-sans text-dark antialiased overflow-x-hidden">
    <!-- SVG 滤镜定义 -->
    <svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" style="position:absolute; overflow:hidden">
        <defs>
            <filter id="glass-distortion" x="0%" y="0%" width="100%" height="100%">
                <feTurbulence type="fractalNoise" baseFrequency="0.008 0.008" numOctaves="2" seed="92" result="noise" />
                <feGaussianBlur in="noise" stdDeviation="2" result="blurred" />
                <feDisplacementMap in="SourceGraphic" in2="blurred" scale="77" xChannelSelector="R" yChannelSelector="G" />
            </filter>
        </defs>
    </svg>
    
    <!-- 头部区域 -->
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <header class="mb-12">
            <div class="flex flex-col md:flex-row justify-center items-center mb-6 relative">
                <!-- 标题居中 -->
                <div class="flex items-center justify-center w-full md:absolute md:left-1/2 md:transform md:-translate-x-1/2">
                    <h1 class="text-[clamp(1.5rem,3vw,2.5rem)] font-bold text-black drop-shadow-lg">墨影Sec导航页</h1>
                </div>
                
                <!-- 天气和IP信息保持在右侧 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full md:w-auto md:ml-auto mt-4 md:mt-0">
                    <!-- IP信息 -->
                    <div class="liquid-glass-card p-4 flex items-center weather-info">
                        <div class="icon-container mr-3">
                            <i class="fa fa-globe text-xl text-primary"></i>
                        </div>
                        <div>
                            <div class="text-xs text-black">当前IP</div>
                            <div id="ip-address" class="text-lg font-medium text-black"><?php echo $ip_address; ?></div>
                            <div id="ip-location" class="text-xs text-black">
                                <?php echo $ip_addr; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 天气信息 -->
                    <div class="liquid-glass-card p-4 flex items-center weather-info">
                        <div class="icon-container mr-3">
                            <i class="fa fa-sun text-xl text-primary"></i>
                        </div>
                        <div>
                            <div class="text-xs text-black">天气</div>
                            <div id="weather-temperature" class="text-lg font-medium text-black"><?php echo isset($weather['tem']) ? $weather['tem'] . '°C' : '获取失败'; ?></div>
                            <div id="weather-condition" class="text-xs text-black">
                                <?php echo isset($weather['wea']) ? $weather['wea'] : '请刷新重试'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 搜索框 - 按钮在右侧 -->
            <div class="relative search-container mx-auto mb-8">
                <div class="liquid-glass-card p-1">
                    <!-- 添加 target="_blank" 属性 -->
                    <form action="https://cn.bing.com/search" method="get" class="flex items-center" target="_blank">
                        <input type="text" name="q" placeholder="搜索..." class="search-input flex-1 bg-transparent py-3 px-4 text-lg focus:outline-none text-black">
                        <button type="submit" class="px-4 text-black hover:text-primary transition-colors">
                            <i class="fa fa-search text-xl"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>
        
        <!-- 主要内容区域 -->
        <main class="grid grid-cols-1 gap-6">
            <?php foreach ($siteGroups as $groupName => $sites): ?>
                <section class="liquid-glass-card p-6">
                    <h2 class="text-xl font-bold mb-4 text-black flex items-center">
                        <i class="fa fa-folder text-primary mr-2"></i>
                        <?php echo $groupName; ?>
                    </h2>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <?php foreach ($sites as $site): ?>
                            <a href="<?php echo $site['url']; ?>" target="_blank" class="site-card p-4 flex flex-col items-center justify-center relative group">
                                <!-- 修改图标显示部分 -->
                                <div class="w-16 h-16 rounded-lg bg-primary/10 flex items-center justify-center mb-2">
                                    <img src="<?php echo $site['ico']; ?>" alt="<?php echo $site['name']; ?> icon" class="w-8 h-8">
                                </div>
                                <span class="text-center text-sm font-medium truncate w-full text-black"><?php echo $site['name']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </main>
    </div>

    <script>
        // 卡片悬停动画
        document.querySelectorAll('.liquid-glass-card, .site-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                card.style.setProperty('--shadow-offset', `${(x - rect.width/2) / 20}px`);
                card.style.setProperty('--shadow-offset-y', `${(y - rect.height/2) / 20}px`);
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.setProperty('--shadow-offset', '0');
                card.style.setProperty('--shadow-offset-y', '0');
            });
        });
        
        // 背景动画控制
        let bgAnimation = true;
        
        // 切换背景动画
        function toggleBgAnimation() {
            bgAnimation = !bgAnimation;
            const bg = document.querySelector('body::before');
            if (bgAnimation) {
                document.body.style.animationPlayState = 'running';
            } else {
                document.body.style.animationPlayState = 'paused';
            }
        }
        
        // 初始化天气信息动画
        setInterval(() => {
            document.querySelectorAll('.weather-info').forEach(info => {
                info.style.transform = `translateY(${Math.random() * 3 - 1.5}px)`;
            });
        }, 3000);
    </script>
</body>
</html>