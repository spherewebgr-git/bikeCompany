import { useEffect, useState } from "react";
import "../../../css/statistics.scss";
import "../../../css/staff-homepage.scss"; // TODO: fix this dependency


export default function TrackStatistics()
{
    const [statistics, setStatistics] = useState(null);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetch("/api/admin/track/statistics")
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error ${response.status}`);
                }

                return response.json();
            })
            .then(data => {
                console.log("Statistics received:", data);
                setStatistics(data);
            })
            .catch(error => {
                console.error("Statistics fetch failed:", error);
                setError(error);
            });
    }, []);

    if (error) {
        return <p>Could not load statistics.</p>;
    }

    if (!statistics) {
        return <p>Loading...</p>;
    }

    // useEffect(() =>
    // {
    //     fetch("/api/admin/track/statistics").then(response => response.json()).then(data => {setStatistics(data);});
    // }, []);

    const locationClassNames =
    [
        "loc-card gradient-45deg-deep-purple-purple",
        "loc-card gradient-45deg-light-green-amber",
        "loc-card gradient-45deg-orange-deep-orange"
    ]

    return (
        <div id="BusinessStatistics">
            <div>
                <h2>Sales Information</h2>
                <div className="row">
                    <div className="statistic-card gradient-45deg-light-blue-cyan white-text">
                        <div className="category">
                            <i className="material-icons background-round mt-5">add_shopping_cart</i>
                            <p>Orders</p>
                        </div>
                        <div className="info">
                            <h5 className="mb-0 white-text">{ statistics.neworders }</h5>
                            <p className="no-margin">New</p>
                            <p>{ statistics.totalorders }</p>
                            </div>
                    </div>
                        <div className="statistic-card gradient-45deg-red-pink white-text">
                            <div className="category">
                                <i className="material-icons background-round mt-5">perm_identity</i>
                                <p>Clients</p>
                            </div>
                            <div className="info">
                                <h5 className="mb-0 white-text">{ statistics.newusers }</h5>
                                <p className="no-margin">New</p>
                                <p>{ statistics.totalusers }</p>
                            </div>
                        </div>
                        
                        <div className="statistic-card gradient-45deg-amber-amber white-text">
                            <div className="category">
                                <i className="material-icons background-round mt-5">timeline</i>
                                <p>Rented</p>
                            </div>
                            <div className="info">
                                <h5 className="mb-0 white-text">{ ((statistics.rents * 100) / statistics.totalorders).toFixed(2) }%</h5>
                                <p className="no-margin">Of Orders</p>
                                <p>{ statistics.rents }</p>
                            </div>
                        </div>

                        <div className="statistic-card gradient-45deg-purple-violet white-text">
                            <div className="category">
                                <i className="material-icons background-round mt-5">timeline</i>
                                <p>Purchases</p>
                            </div>
                            <div className="info">
                                <h5 className="mb-0 white-text">{ ((statistics.purchases * 100) / statistics.totalorders).toFixed(2) }%</h5>
                                <p className="no-margin">Of Orders</p>
                                <p>{ statistics.purchases }</p>
                            </div>
                        </div>
                        
                        <div className="statistic-card gradient-45deg-green-teal white-text">
                            <div className="category">
                                <i className="material-icons background-round mt-5">attach_money</i>
                                <p>Profit</p>
                            </div>
                            <div className="info">
                                <h5 className="mb-0 white-text">{ statistics.newprofit }</h5>
                                <p className="no-margin">Today</p>
                                <p>{ statistics.totalprofit.toFixed(2) } €</p>
                            </div>
                        </div>

                        <div id="location-stats">
                            <div className="row">
                                {statistics.locsales.map((location, index) => (
                                    <div className={locationClassNames[index]} key={location.location}>
                                        <h6><b>{location.location}</b></h6>
                                        <h6>{location.sales} Total Sales</h6>
                                        <br />
                                        <p>Total Profit:</p>
                                        <h5>{location.profit.toFixed(2)} €</h5>
                                    </div>
                                ))}
                            </div>
                        </div>

                </div>
            </div>
        </div>
    );
}