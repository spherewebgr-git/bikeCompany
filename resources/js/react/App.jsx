import { createRoot } from 'react-dom/client';
import Bikes from './pages/Bikes';


function App(){

    return (
        <Bikes />
    );

}


createRoot(
    document.getElementById('app')
)
    .render(
        <App />
    );
