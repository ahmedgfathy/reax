import i18next from 'i18next';
import LanguageDetector from 'i18next-browser-languagedetector';

// Initialize i18next
i18next
  .use(LanguageDetector)
  .init({
    fallbackLng: 'en',
    debug: false,
    resources: {
      en: {
        translation: {} // Will be loaded dynamically
      },
      ar: {
        translation: {} // Will be loaded dynamically
      }
    },
    detection: {
      order: ['cookie', 'localStorage', 'htmlTag'],
      lookupCookie: 'locale',
      caches: ['cookie']
    },
    interpolation: {
      escapeValue: false
    }
  });

// Function to load translations from the server
export const loadTranslations = async (locale) => {
  try {
    const response = await fetch(`/api/translations/${locale}`);
    const translations = await response.json();
    
    i18next.addResourceBundle(locale, 'translation', translations, true, true);
  } catch (error) {
    console.error('Failed to load translations:', error);
  }
};

// Translation function
export const __ = (key, options = {}) => {
  return i18next.t(key, options);
};

// Set direction based on language
export const setDirection = () => {
  const isRTL = i18next.language === 'ar';
  document.documentElement.dir = isRTL ? 'rtl' : 'ltr';
  document.documentElement.lang = i18next.language;
};

// Initialize - load current language
const currentLocale = document.documentElement.lang || 'en';
loadTranslations(currentLocale);
setDirection();

// Export for global use
window.__ = __;

export default i18next;
