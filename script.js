// التبديل بين اللغتين بناءً على الزر
const languageToggle = document.getElementById('language-toggle');

// تحقق من اللغة المخزنة في LocalStorage
const userLanguage = localStorage.getItem('language') || 'ar'; // العربية كافتراضي

// تحديث اللغة على الصفحة بناءً على اللغة المخزنة
function setLanguage(language) {
    if (language === 'ar') {
        // تغيير النصوص إلى العربية
        document.getElementById('product-title').textContent = 'منتجاتنا';
        document.getElementById('product-name').textContent = 'اسم المنتج';
        document.getElementById('product-description').textContent = 'وصف مختصر للمنتج';
        document.getElementById('buy-button').textContent = 'شراء الآن';
        document.getElementById('language-toggle').textContent = 'اللغة الإنجليزية';
    } else {
        // تغيير النصوص إلى الإنجليزية
        document.getElementById('product-title').textContent = 'Our Products';
        document.getElementById('product-name').textContent = 'Product Name';
        document.getElementById('product-description').textContent = 'Short description of the product';
        document.getElementById('buy-button').textContent = 'Buy Now';
        document.getElementById('language-toggle').textContent = 'اللغة العربية';
    }
    // حفظ اللغة المختارة في LocalStorage
    localStorage.setItem('language', language);
}

// التحقق من اللغة عند تحميل الصفحة
setLanguage(userLanguage);

// تغيير اللغة عند الضغط على الزر
languageToggle.addEventListener('click', function () {
    const newLanguage = userLanguage === 'ar' ? 'en' : 'ar';
    setLanguage(newLanguage);
});
