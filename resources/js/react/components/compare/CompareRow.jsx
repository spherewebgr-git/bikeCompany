export default function CompareRow({
                                       label,
                                       leftValue,
                                       rightValue,
                                   }) {
    return (
        <div className="compare-row">

            <div className="compare-row__label">
                {label}
            </div>

            <div className="compare-row__value">
                {leftValue ?? '-'}
            </div>

            <div className="compare-row__value">
                {rightValue ?? '-'}
            </div>

        </div>
    );
}
