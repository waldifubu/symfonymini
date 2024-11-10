import React, {useEffect} from "react";
import {Card, Container} from "reactstrap";
import Loading from "../components/ToursComponents/Loading";
import Tours from "../components/ToursComponents/Tours";

const url = 'https://www.course-api.com/react-tours-project';

function ToursPage() {
    const [loading, setLoading] = React.useState(true);
    const [tours, setTours] = React.useState([]);

    const fetchTours = async (): Promise<any> => {
        setLoading(true);
        try {
            const response: Response = await fetch(url, {
                mode: 'no-cors',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            const tours: Response = await response.json();
            // @ts-ignore
            setTours(tours);
            setLoading(false);
        } catch (error) {
            setLoading(false);
            console.log(error);
        }
    }

    // @ts-ignore
    useEffect(() => {
        fetchTours();
    }, []);

    if (loading) {
        return (
            <Loading/>
        );
    }

    return (
        <Container>
            <Card className="text-center">
                <h1>Tours</h1>
                <main>
                    <p>Some tours</p>
                    <Tours tours={tours}/>
                </main>
            </Card>
        </Container>
    );
}

export default ToursPage;