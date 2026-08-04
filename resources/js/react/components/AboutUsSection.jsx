import { useEffect, useRef, useState } from 'react';

// ============ Βοηθητικό component: αριθμός που μετράει όταν μπαίνει στο viewport ============
function AnimatedStat({ value, label }) {
    const [count, setCount] = useState(0);
    const [hasAnimated, setHasAnimated] = useState(false);
    const ref = useRef(null);

    // Εξάγουμε το καθαρό νούμερο από strings όπως "150+" ή "2,000+"
    const numericValue = parseInt(value.replace(/[^0-9]/g, ''), 10);
    const suffix = value.replace(/[0-9,]/g, ''); // κρατάει το "+" στο τέλος

    useEffect(() => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting && !hasAnimated) {
                    setHasAnimated(true);
                    const duration = 1500;
                    const steps = 40;
                    const increment = numericValue / steps;
                    let current = 0;

                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= numericValue) {
                            setCount(numericValue);
                            clearInterval(timer);
                        } else {
                            setCount(Math.floor(current));
                        }
                    }, duration / steps);
                }
            },
            { threshold: 0.5 }
        );

        if (ref.current) observer.observe(ref.current);
        return () => observer.disconnect();
    }, [hasAnimated, numericValue]);

    return (
        <div className="about-stat" ref={ref}>
            <span className="about-stat__value">
                {count.toLocaleString()}{suffix}
            </span>
            <span className="about-stat__label">{label}</span>
        </div>
    );
}

// ============ Κύριο component ============
export default function AboutSection({ image }) {
    const mediaRef = useRef(null);
    const [tilt, setTilt] = useState({ x: 0, y: 0 });

    const handleMouseMove = (e) => {
        if (!mediaRef.current) return;
        const rect = mediaRef.current.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;
        setTilt({ x: x * 10, y: y * -10 }); // μέγιστη γωνία ±10deg
    };

    const handleMouseLeave = () => setTilt({ x: 0, y: 0 });

    const values = [
        { icon: 'fa-check-circle', text: 'Quality checked' },
        { icon: 'fa-clock', text: 'Flexible rentals' },
        { icon: 'fa-life-ring', text: 'Local support' },
        { icon: 'fa-refresh', text: 'Fresh stock' },
    ];

    const stats = [
        { value: '150+', label: 'Bikes available' },
        { value: '2,000+', label: 'Happy riders' },
        { value: '10+', label: 'Years of experience' },
    ];

    return (
        <section className="about-section" id="about">
            <div className="nav-container">
                <div className="about-layout">

                    <div
                        className="about-media"
                        ref={mediaRef}
                        onMouseMove={handleMouseMove}
                        onMouseLeave={handleMouseLeave}
                        style={{
                            transform: `perspective(1000px) rotateX(${tilt.y}deg) rotateY(${tilt.x}deg)`,
                        }}
                    >
                        <img src={image} alt="Trail Bike workshop" />
                        <div className="about-media__badge">
                            <span className="about-media__badge-line">Tuning since</span>
                            <span className="about-media__badge-year">10+ yrs</span>
                        </div>
                    </div>

                    <div className="about-copy">
                        <span className="about-eyebrow">
                            <span className="about-eyebrow__dot"></span>
                            Trail Bike Workshop
                        </span>

                        <h2 className="about-heading">
                            Built on trails.
                            <span className="about-heading__accent">Tuned by hand.</span>
                        </h2>

                        <p className="about-text">
                            We started as a handful of friends who couldn't stay off two wheels.
                            Now we're a full workshop and store — inspecting, tuning, and servicing
                            every bike before it leaves our hands, so you can focus on the ride.
                        </p>
                        <p className="about-text">
                            From city cruisers to full-suspension trail bikes, our catalog keeps
                            growing. Whatever you ride, we're here with the right bike and honest advice.
                        </p>

                        <ul className="about-values">
                            {values.map((v, i) => (
                                <li
                                    key={v.icon}
                                    className="about-values__item"
                                    style={{ '--delay': `${i * 0.08}s` }}
                                >
                                    <i className={`fa ${v.icon}`}></i>
                                    <span>{v.text}</span>
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>

                <div className="about-stats">
                    {stats.map((s) => (
                        <AnimatedStat key={s.label} value={s.value} label={s.label} />
                    ))}
                </div>

            </div>
        </section>
    );
}
