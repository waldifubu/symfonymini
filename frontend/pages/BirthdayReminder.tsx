import React, {useState} from "react";
import {Button, Card, Container} from "reactstrap";
import data from "../components/BirthdayComponents/data";
import List from "../components/BirthdayComponents/List";
import "../components/BirthdayComponents/birthday.css";

function BirthdayReminder() {
    const [people, setPeople] = useState(data);

    return (
        <Container>
            <Card className="text-center">
                <h2>Birthday Reminder</h2>
                <main>
                    <section>
                        <h3>{people.length} birthdays today</h3>
                        <List people={people}/>
                        <Button block={true} color="info" onClick={() => setPeople([])}>Clear all</Button>
                    </section>
                </main>
            </Card>
        </Container>
    );
}

export default BirthdayReminder;