"use strict";

import React, {useEffect, useState} from "react";
import {Card, CardBody, CardHeader, Container} from "reactstrap";
import {Link, useParams} from "react-router-dom";
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faPlus} from '@fortawesome/free-solid-svg-icons';
import axios, {AxiosResponse} from "axios";


const SeriesEdit: React.FC = () => {
    const {id} = useParams<{ id: string }>(); // TypeScript typing

    type Series = {
        id: number;
        title: string;
        author: string;
        created: any;
        count: number;
        last?: any;
    }

    const [series, setSeries] = useState<Partial<Series>>({});
    const [isLoading, setIsLoading] = React.useState(true);

    async function fetchSeriesList(): Promise<void> {
        const response: AxiosResponse<Series> = await axios.get(`/api/series/edit/${id}`);
        setSeries(response.data)

    }

    useEffect(() => {
        fetchSeriesList().then(rr => setIsLoading(false));
    }, []);

    if (isLoading) {
        return (
            <Container>
                <Card>
                    <CardHeader className="text-center">
                        <h3>Loading series. Please wait ...</h3>
                    </CardHeader>
                </Card>
            </Container>
        );
    }


    return (
        <Container>
            <Card>
                <CardHeader>
                    <Link
                        className="btn btn-outline-primary"
                        to="/create"
                    >
                        <FontAwesomeIcon icon={faPlus}/> {id} {series.title} {series.id}
                    </Link>
                </CardHeader>
                <CardBody>
                    COOLE
                </CardBody>
            </Card>
        </Container>
    )
}

export default SeriesEdit;
