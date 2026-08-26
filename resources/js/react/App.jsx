import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';

// LAYOUTS
import LayoutAdmin from './components/LayoutAdmin';
import LayoutPublic from './components/LayoutPublic';

// PUBLIC PAGES
import AboutUs from './pages/AboutUs';

import Bikes from './pages/Bikes';

import MyOrders from './pages/MyOrders';

import MyHistory from './pages/MyHistory';

// ADMIN PAGES
import ManageBikes from './pages/ManageBikes';
import EditBike from './pages/EditBike';
import CreateBike from './pages/CreateBike';

import ManageCategories from './pages/ManageCategories';

import ManageOrders from './pages/ManageOrders';

import ActiveRentals from './pages/ActiveRentals';

import TrackOrders from './pages/TrackOrders';

import TrackStatistics from './pages/TrackStatistics';



function App()
{
    return (
        <BrowserRouter>
            <Routes>

            {/* PUBLIC PAGES */}
                <Route element={<LayoutPublic />}>
                    <Route path="/about-us-react" element={<AboutUs />} />
                    <Route path="/test/bikes" element={<Bikes />} />

                    <Route path="/profile/myorders" element={<MyOrders />} />
                    <Route path="/profile/myhistory" element={<MyHistory />} />
                </Route>

            {/* ADMIN PAGES */}
                <Route element={<LayoutAdmin />}>
                    <Route path="/admin/manage/products" element={<ManageBikes  />}/>
                    <Route path="/admin/manage/products/edit/:id" element={<EditBike />}/>
                    <Route path="/admin/manage/products/create" element={<CreateBike />}/>
                    <Route path="/admin/manage/categories" element={<ManageCategories />}/>
                    <Route path="/admin/manage/pending-orders" element={<ManageOrders />}/>
                    <Route path="/admin/track/activerentals" element={<ActiveRentals />}/>
                    <Route path="/admin/track/past-orders" element={<TrackOrders />}/>
                    <Route path="/admin/track/statistics" element={<TrackStatistics />}/>
                </Route>

            </Routes>
        </BrowserRouter>
    );
}

createRoot(document.getElementById('app')).render(<App />);
