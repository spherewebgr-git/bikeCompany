const icons = {
    location: "fa-solid fa-location-dot",
    phone: "fa-solid fa-phone",
    email: "fa-solid fa-envelope",
    social: "fa-brands fa-instagram",
};

export default function ContactBoxes({ items = [] }) {

    const renderValue = (item) => {

        switch (item.type) {

            case "phone":
                return (
                    <a href={`tel:${item.value}`}>
                        {item.value}
                    </a>
                );

            case "email":
                return (
                    <a href={`mailto:${item.value}`}>
                        {item.value}
                    </a>
                );

            case "location":
                return (
                    <a
                        href={`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(item.value)}`}
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        {item.value}
                    </a>
                );

            case "social":
                return (
                    <a
                        href={item.link}
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        {item.value}
                    </a>
                );

            default:
                return <span>{item.value}</span>;
        }
    };

    return (
        <div class="container">
            <h2 className="contact-boxes-title">You can find us here</h2>
            <div className="contact-boxes">



                    {items.slice(0, 4).map((item, index) => (

                        <div className="contact-box" key={index}>

                            <i className={icons[item.type]}></i>

                            <h4>{item.title}</h4>

                            {renderValue(item)}

                        </div>

                    ))}



            </div>
        </div>
    );
}
