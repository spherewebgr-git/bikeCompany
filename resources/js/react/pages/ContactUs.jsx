import { createRoot } from 'react-dom/client';
import ContactBoxes from "../components/ContactBoxes";

function ContactUs() {
    return (
        <div>
            <ContactBoxes
                items={[
                    {
                        type: "location",
                        title: "Location",
                        value: "Athens, Greece"
                    },
                    {
                        type: "phone",
                        title: "Phone",
                        value: "+30 2101234567"
                    },
                    {
                        type: "email",
                        title: "Email",
                        value: "info@bikecompany.gr"
                    },
                    {
                        type: "social",
                        title: "Social",
                        value: "@bikecompany", link: "https://instagram.com/bikecompany"
                    },
                ]}
            />
        </div>
    );
}

const el = document.getElementById('contact-us-boxes');
if (el) createRoot(el).render(<ContactUs />);
