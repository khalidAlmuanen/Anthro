<div align="center">

# 🌿 أنثرو | Anthro — منصة وثيم الأنثروبولوجيا السعودية

**منصة رقمية مخصصة وثيم ووردبريس فريد للمحتوى الأنثروبولوجي، الثقافي، والبودكاست في المملكة العربية السعودية**

[![WordPress Theme](https://img.shields.io/badge/WordPress-Custom_Theme-21759B?style=for-the-badge&logo=wordpress&logoColor=white)](https://wordpress.org)
[![PHP Version](https://img.shields.io/badge/PHP-8.x_Compatible-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![HTML5 & Vanilla JS](https://img.shields.io/badge/HTML5_%26_JS-ES6+-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org)
[![RTL Supported](https://img.shields.io/badge/RTL-Native_Support-008080?style=for-the-badge)](https://developer.mozilla.org/en-US/docs/Glossary/RTL)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

---

</div>

## 📌 عن المشروع

مشروع **أنثرو (Anthro)** هو منصة رقمية متكاملة تهدف إلى إبراز ونشر الدراسات والمقالات والبودكاست المتخصص في الأنثروبولوجيا والثقافة السعودية. تم تطوير هذا المشروع وفق أعلى معايير التصميم والتطوير البرمجي لتقديم تجربة قراءة واستماع استثنائية.

يتكون المشروع من قسمين رئيسيين:
1. **النموذج الأولي للويب (Interactive Web Prototype)**: واجهات HTML5/CSS3/JavaScript كاملة ومصممة بدقة متناهية للاستعراض المباشر والتطوير السريع.
2. **ثيم ووردبريس مخصص بالكامل (`wordpress-theme/`)**: مظهر ووردبريس (Custom WordPress Theme) احترافي، مبني بدون منشئات صفحات ثقيلة (Page Builders)، ويقدم أداءً عالياً، توافقاً تاماً مع محركات البحث (SEO)، ودعماً كاملاً للوحة التحكم وإعدادات Customize.

---

## ✨ المميزات الرئيسية

- 🎨 **هوية بصرية فريدة (Visual Identity)**: ألوان ترابية وطبيعية متوازنة (الزيتوني، النحاسي، النحاسي المحروق، الخلفيات الرملية الناعمة).
- 🎙️ **قسم بودكاست تفاعلي (Integrated Podcast Engine)**: مشغل صوت مخصص، شبكة حلقات، وإمكانية الربط مع Spotify و Apple Podcasts.
- 📖 **تجربة قراءة سينمائية (Rich Reading Experience)**: تصميم هيرو (Hero Section) جذاب للمقالات المميزة، حساب وقت القراءة، اقتباسات افتتاحيّة، وهيكلية نصوص مريحة للعين.
- 📱 **استجابة كاملة (Fully Responsive & Mobile Native)**: تجربة متوافق مع كافة أحجام الشاشات والأجهزة الهواتف والأجهزة اللوحية.
- ⚡ **أداء فائق وسريع**: كود نظيف وخفيف وخالٍ من المكتبات الفائضة لتحقيق أقصى درجات السرعة في Core Web Vitals.
- 🌐 **دعم كامل للغة العربية (RTL First)**: خطوط عربية حديثة (Outfit / Cairo / Naskh) واتجاهات متناسقة مع إمكانية التدويل (i18n).

---

## 🗂️ هيكلية المشروع (Project Architecture)

```
Anthro/
├── index.html               # الصفحة الرئيسية للنموذج الأولي
├── about.html               # صفحة عن أنثرو
├── archive.html             # صفحة الأرشيف العام
├── author.html              # صفحة الكاتب/الأنثروبولوجي
├── category.html            # صفحة التصنيف
├── contact.html             # صفحة التواصل
├── podcast.html             # صفحة البودكاست الرئيسية
├── search.html              # صفحة نتائج البحث
├── single.html              # صفحة المقال الفردي
├── style.css                # النمط البرمجي العام ونظام الألوان
├── inner-pages.css          # أنماط الصفحات الفرعية
├── archive.css / podcast.css / search.css / single.css
├── script.js / archive.js / podcast.js / search.js / single.js
├── assets/                  # الصور والأصول البصرية للنموذج
└── wordpress-theme/         # ثيم ووردبريس المخصص (WordPress Theme)
    ├── README.md            # دليل تثبيت واستخدام الثيم للعميل والمطور
    ├── functions.php        # منطق الثيم والـ Custom Post Types
    ├── header.php / footer.php
    ├── front-page.php / single.php / archive.php / search.php / author.php
    ├── template-parts/      # الأجزاء الميكروية (card-article, newsletter)
    └── assets/              # ملفات CSS/JS مدمجة ومحسنة للثيم
```

---

## 🚀 طريقة التشغيل والاستخدام

### 1️⃣ معاينة النموذج الأولي (Static Prototype)
يمكنك فتح ملف `index.html` مباشرة في أي متصفح، أو تشغيل خادم محلي مثل `live-server`:
```bash
# باستخدام Live Server في Node.js
npx live-server
```

### 2️⃣ تثبيت ثيم ووردبريس (`wordpress-theme`)
1. قم بنسخ مجلد `wordpress-theme/` وأعد تسميته إلى `anthro`.
2. ارفع المجلد إلى مسار الثيمات في موقع ووردبريس الخاط بك:
   `wp-content/themes/anthro/`
3. من لوحة تحكم ووردبريس: **المظهر** ← **المظاهر** ← قم بتفعيل ثيم **أنثرو | Anthro**.
4. للمزيد من التفاصيل حول تخصيص الثيم وإضافة المقالات والبودكاست، يرجى مراجعة [دليل الثيم الخاص](wordpress-theme/README.md).

---

## 🛠️ التقنيات المستخدمة (Tech Stack)

- **Frontend**: HTML5, CSS3 (Custom Properties & Modern Grid/Flexbox), JavaScript (ES6+ Vanilla JS).
- **Backend & CMS**: PHP 8.x, WordPress REST API & Custom Post Types (`podcast_episode`).
- **Typography**: Google Fonts (Outfit, Cairo).
- **Icons & Graphics**: Custom SVG Icons & Clean UI Components.

---

## 📄 الترخيص (License)

هذا المشروع مرخص بموجب ترخيص [MIT License](LICENSE).

---

<div align="center">

**طُوّر بكل شغف لصالح أنثرو — أنثروبولوجيا سعودية 🌴**  
مستودع المشروع: [github.com/khalidAlmuanen/Anthro](https://github.com/khalidAlmuanen/Anthro)

</div>
