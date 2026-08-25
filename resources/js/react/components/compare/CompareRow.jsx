export default function CompareRow({
                                       label,
                                       values,
                                   }) {
    return (
        <div className="compare-row">

            <div className="compare-row__label">
                {label}
            </div>

            {values.map(function (value, index) {
                return (
                    <div
                        key={index}
                        className="compare-row__value"
                    >
                        {value ?? '-'}
                    </div>
                );
            })}

        </div>
    );
}
