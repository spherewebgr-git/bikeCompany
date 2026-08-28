import "../../../../css/order-status.scss";

export default function OrderStatus({statuses, order})
{
    return (
        <div className="order-status">
        {statuses.map(status => ( status.step > 0 &&
            order.status.step > status.step ?
                <div className="stepinfo">
                    <p className="past statpoint">{ status.step }</p>
                    <p className="past statdesc">{ status.name }</p>
                </div>
            : order.status.step === status.step ?
                <div className="stepinfo">
                    <p className="current statpoint">{ status.step }</p>
                    <p className="current statdesc">{ status.name }</p>
                </div>
            :
                <div className="stepinfo">
                    <p className="statpoint">{ status.step }</p>
                    <p className="statdesc">{ status.name }</p>
                </div>
        ))}
        </div>
    );
}