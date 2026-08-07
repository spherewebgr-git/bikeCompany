import CompareRow from './CompareRow';

export default function CompareTable({
                                         bikes,
                                         removingBikeId,
                                         onRemove,
                                     }) {
    const firstBike = bikes[0] ?? null;
    const secondBike = bikes[1] ?? null;
    const thirdBike = bikes[2] ?? null;

    function formatPrices(bike) {
        if (!bike?.prices?.length) {
            return '-';
        }

        return bike.prices
            .map(function (price) {
                const description = price.description
                    ? ` ${price.description}`
                    : '';

                return `${price.price} €${description}`;
            })
            .join(' / ');
    }

    function renderBike(bike, emptyText) {
        if (!bike) {
            return (
                <div className="compare-bike">
                    <div className="compare-bike__empty">
                        {emptyText}
                    </div>
                </div>
            );
        }

        return (
            <div className="compare-bike">

                <div className="compare-bike__image">
                    {bike.image ? (
                        <img
                            src={bike.image}
                            alt={bike.brand ?? 'Bike'}
                        />
                    ) : (
                        <i className="fa-solid fa-bicycle" />
                    )}
                </div>

                <h3>{bike.brand ?? 'Bike'}</h3>

                <p>{bike.type ?? '-'}</p>

                <button
                    type="button"
                    className="compare-bike__remove"
                    onClick={() => onRemove(bike.id)}
                    disabled={removingBikeId === bike.id}
                >
                    <i
                        className={
                            removingBikeId === bike.id
                                ? 'fa-solid fa-spinner fa-spin'
                                : 'fa-solid fa-xmark'
                        }
                    />

                    Remove
                </button>

            </div>
        );
    }

    return (
        <div className="compare-table">

            <div className="compare-table__bikes">

                <div className="compare-table__label-column">
                    <span>Bike</span>
                </div>

                {renderBike(firstBike, 'Add a bike')}
                {renderBike(secondBike, 'Add another bike')}
                {renderBike(thirdBike, 'Add another bike')}

            </div>

            <div className="compare-table__specs">

                <CompareRow
                    label="Brand"
                    values={[
                        firstBike?.brand,
                        secondBike?.brand,
                        thirdBike?.brand,
                    ]}
                />

                <CompareRow
                    label="Type"
                    values={[
                        firstBike?.type,
                        secondBike?.type,
                        thirdBike?.type,
                    ]}
                />

                <CompareRow
                    label="Colour"
                    values={[
                        firstBike?.colour,
                        secondBike?.colour,
                        thirdBike?.colour,
                    ]}
                />

                <CompareRow
                    label="Gears"
                    values={[
                        firstBike?.gears
                            ? `${firstBike.gears} speeds`
                            : '-',

                        secondBike?.gears
                            ? `${secondBike.gears} speeds`
                            : '-',

                        thirdBike?.gears
                            ? `${thirdBike.gears} speeds`
                            : '-',
                    ]}
                />

                <CompareRow
                    label="Provision"
                    values={[
                        firstBike?.provision,
                        secondBike?.provision,
                        thirdBike?.provision,
                    ]}
                />

                <CompareRow
                    label="Price"
                    values={[
                        formatPrices(firstBike),
                        formatPrices(secondBike),
                        formatPrices(thirdBike),
                    ]}
                />

                <CompareRow
                    label="Availability"
                    values={[
                        firstBike
                            ? firstBike.quantity > 0
                                ? `${firstBike.quantity} in stock`
                                : 'Out of stock'
                            : '-',

                        secondBike
                            ? secondBike.quantity > 0
                                ? `${secondBike.quantity} in stock`
                                : 'Out of stock'
                            : '-',

                        thirdBike
                            ? thirdBike.quantity > 0
                                ? `${thirdBike.quantity} in stock`
                                : 'Out of stock'
                            : '-',
                    ]}
                />

                <CompareRow
                    label="SKU"
                    values={[
                        firstBike?.sku,
                        secondBike?.sku,
                        thirdBike?.sku,
                    ]}
                />

            </div>

            <div className="compare-table__actions">

                <div></div>

                <div>
                    {firstBike && (
                        <a
                            href={firstBike.show_url}
                            className="btn btn-fill btn-md"
                        >
                            View Bike
                        </a>
                    )}
                </div>

                <div>
                    {secondBike && (
                        <a
                            href={secondBike.show_url}
                            className="btn btn-fill btn-md"
                        >
                            View Bike
                        </a>
                    )}
                </div>

                <div>
                    {thirdBike && (
                        <a
                            href={thirdBike.show_url}
                            className="btn btn-fill btn-md"
                        >
                            View Bike
                        </a>
                    )}
                </div>

            </div>

        </div>
    );
}
