import React from 'react';
import {Card, Container} from "reactstrap";

const Loading = () => {
    return (
        <Container>
            <Card className="text-center">
                <div className="loading"></div>
            </Card>
        </Container>
    );
};

export default Loading;