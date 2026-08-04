import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Bikes from './pages/Bikes';
import AboutUs from './pages/AboutUs';

function App() {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/test/bikes" element={<Bikes />} />
                <Route path="/about-us-react" element={<AboutUs />} />
            </Routes>
        </BrowserRouter>
    );
}

createRoot(document.getElementById('app')).render(<App />);
