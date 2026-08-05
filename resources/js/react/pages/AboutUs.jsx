import PageHero from '../components/PageHero.jsx';
import FeaturedBikes from '../components/FeaturedBikes.jsx';
import AboutUsSection from '../components/AboutUsSection.jsx';
import aboutImage from '../../../images/about-image.jpg';
import PageHeroImage from '../../../images/page-hero.jpg';
import PageHeroVideo from '../../../videos/about-us-video.mp4';


export default function AboutUs()
{
    return (
        <div className="app-wrapper">
            <main className="page-content">
                <PageHero
                    eyebrow="Trail Bike Workshop"
                    title="About Us"
                    description="What started as a handful of friends who couldn't stay off two wheels has grown into a full workshop and store. Every bike that leaves our hands is inspected, tuned, and serviced by people who actually ride because we believe good gear starts with people who understand the trail."
                    backgroundImage={PageHeroImage}
                    backgroundVideo={PageHeroVideo}
                />

                <AboutUsSection image={aboutImage} />

                <FeaturedBikes/>


            </main>
        </div>
    );
}
