import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';

// LAYOUTS
import LayoutAdmin from './components/LayoutAdmin';
import LayoutPublic from './components/LayoutPublic';

// PUBLIC PAGES
import AboutUs from './pages/AboutUs';
import Bikes from './pages/Bikes';

// ADMIN PAGES
import ManageBikes from './pages/ManageBikes';
import EditBike from './pages/EditBike';
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
                    <Route path="/admin/manage/products" element={<ManageBikes/>}/>
                    <Route path="/admin/manage/products/edit/:id" element={<EditBike />}/>
                    <Route path="/admin/track/activerentals" element={<ActiveRentals/>}/>
                </Route>

            </Routes>
        </BrowserRouter>
    );
}

createRoot(document.getElementById('app')).render(<App />);
