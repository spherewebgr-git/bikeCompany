import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import LayoutAdmin from './components/LayoutAdmin';
import LayoutPublic from './components/LayoutPublic';
import AboutUs from './pages/AboutUs';
import Bikes from './pages/Bikes';
import ActiveRentals from './pages/ActiveRentals';


function App()
{
    return (
        <BrowserRouter>
            <Routes>

            {/* PUBLIC PAGES */}
                <Route element={<LayoutPublic />}>
                    <Route path="/about-us-react" element={<AboutUs />} />
                    <Route path="/test/bikes" element={<Bikes />} />
                </Route>

            {/* ADMIN PAGES */}
                <Route element={<LayoutAdmin/>}>
                    <Route path="/staff/activerentals" element={<ActiveRentals/>}/>
                </Route>
            
            </Routes>
        </BrowserRouter>
    );
}

createRoot(document.getElementById('app')).render(<App />);
