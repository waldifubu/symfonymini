"use strict";

import React, {Key} from "react";
import {Card, CardBody, CardHeader, Container, Table} from "reactstrap";
import axios, {AxiosResponse} from "axios";
import {Link} from "react-router-dom";
import {useGlobalContext} from "../components/GlobalContext";
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faEdit, faPlus} from '@fortawesome/free-solid-svg-icons';
import moment from "moment";
import {useQuery} from "@tanstack/react-query";

const SeriesList: React.FC = () => {
    const {name} = useGlobalContext();

    type Series = {
        id: number;
        title: string;
        author: string;
        created: any;
        count: number;
        last?: any;
    }

    async function fetchSeriesList(): Promise<Series[]> {
        const response: AxiosResponse<Series[]> = await axios.get('/api/series');
        return response.data;
    }

    // Define fallback data
    const fallbackSeriesList: Series[] = [
        {
            id: 1,
            title: "waldi",
            author: "fallback",
            created: {date: "2021-09-01T00:00:00+00:00"},
            count: 0
        }
    ];

    // Use React Query to fetch data
    let {data: fetchedData, isLoading, error} = useQuery<Series[], Error>({
        queryKey: ['series'],
        queryFn: fetchSeriesList
    });

    // Determine which data to use
    const seriesList: Series[] | undefined = error ? fallbackSeriesList : fetchedData;

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
                        <FontAwesomeIcon icon={faPlus}/> Create new podcast
                    </Link>
                </CardHeader>
                <CardBody>
                    {seriesList && (
                        <Table className="" hover={true}>
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Erstellt</th>
                                <th>Last Episode</th>
                                <th>Episodes</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            {seriesList.map((series: Series, key: Key) => (
                                <tr key={key}>
                                    {/* Table rows remain the same */}
                                    <td>{series.id}</td>
                                    <td>{series.title}</td>
                                    <td>{series.author}</td>
                                    <td>{moment(series.created.date).format('DD.MM.YYYY')}</td>
                                    <td>{series.last ? moment(series.created.date).format('DD.MM.YYYY') : '-'}</td>
                                    <td>{series.count}</td>
                                    <td>
                                        <Link
                                            className="btn btn-outline-success me-1"
                                            to={`/episode/add/${series.id}`}
                                        >
                                            <FontAwesomeIcon icon={faPlus}/>
                                        </Link>
                                        <Link
                                            className="btn btn-outline-primary"
                                            to={`/series/edit/${series.id}`}
                                        >
                                            <FontAwesomeIcon icon={faEdit}/>
                                        </Link>
                                    </td>
                                </tr>
                            ))}
                            </tbody>
                        </Table>
                    )}
                    {name && <p>Hier ist {name}</p>}
                    <Link className="btn btn-outline-primary" to="/people">
                        People
                    </Link>
                </CardBody>
            </Card>
        </Container>
    );
};

export default SeriesList;
