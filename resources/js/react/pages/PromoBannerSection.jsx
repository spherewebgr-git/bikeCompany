import { createRoot } from 'react-dom/client';
import PromoBanner from '../components/banners/PromoBanner';

const element = document.getElementById('promo-banner');

if (element) {
    createRoot(element).render(
        <PromoBanner />
    );
}
