"use strict";

import React, {useCallback, useEffect, useState} from "react";
import {Card, CardBody, CardHeader, Container, Table} from "reactstrap";
import axios from "axios";
import Swal from 'sweetalert2'
import {Link} from "react-router-dom";
import {useGlobalContext} from "../components/GlobalContext";
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faEdit, faPlus} from '@fortawesome/free-solid-svg-icons';
import moment from "moment";
import {Simulate} from "react-dom/test-utils";
import error = Simulate.error;
import {useQueryClient, useQuery} from "@tanstack/react-query";

const SeriesList: React.FC = () => {

    const [seriesList, setSeriesList] = useState<Series[]>([])
    const [isLoading, setIsLoading] = useState(true);
    const {name} = useGlobalContext();

    const metadata = {
        title: 'Show all projects',
        description: 'Show all projects in Symfony'
    }

    type Series = {
        id: number;
        title: string;
        author: string;
        created: any;
        count: number;
        last?: any;

        /*
        constructor(data: { id: number, name: string; author: string, created: any }) {
            this.id = data.id;
            this.title = data.name;
            this.author = data.author
            this.created = data.created
        }
         */
    }

    // Access the client
    const queryClient = useQueryClient()

        const fetchSeriesList = useCallback(async () => {
        console.log('Fetch mich')
        await axios.get('/api/series')
            .then(function (response) {
                setSeriesList(response.data);
                return response.data;
            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: error,
                    showConfirmButton: false,
                    timer: 2100
                })
                    .then(() => {
                        let array: Series[] = [
                            {
                                "id": 1,
                                "title": "waldi",
                                "author": "fallback",
                                "created": {"date": "2021-09-01T00:00:00+00:00"},
                                "count": 0
                            }
                        ];
                        // @ts-ignore
                        setSeriesList(array)
                    })
            })
    }, []);

    // Queries
    const query = useQuery({ queryKey: ['todos'], queryFn: fetchSeriesList })
    // console.log(query)


    useEffect(() => {
        //fetchSeriesList();
        /*
        const {
            data: seriesList,
            error,
            isLoading,
        } = useQuery("postsData", fetchSeriesList);
         */
    }, []);

    const dateTime = "2025-03-08'T'23:36:08";

     // if (isLoading) return <div>Fetching posts...</div>;
    // if (error) return <div>An error occurred: {error.message}</div>;

    return (
        <Container>
            <Card>
                <CardHeader>
                    <Link
                        className="btn btn-outline-primary"
                        to="/create"><FontAwesomeIcon icon={faPlus}/> Create new podcast
                    </Link>
                </CardHeader>
                <CardBody>
                    {seriesList.length > 0 && <Table className="" hover={true}>
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
                        {seriesList.map((series: Series, key: number) => {
                            return (
                                <tr key={key}>
                                    <td>{series.id}</td>
                                    <td>{series.title}</td>
                                    <td>{series.author}</td>
                                    <td>{moment(series.created.date).format('DD.MM.YYYY')}</td>
                                    <td>{series.last ? moment(series.created.date).format('DD.MM.YYYY') : '-'}</td>
                                    <td>{series.count}</td>
                                    <td>
                                        <Link
                                            className="btn btn-outline-success me-1"
                                            // to={`/streams/${stream.id}/edit`}>
                                            to={`/episode/add/${series.id}`}>
                                            <FontAwesomeIcon icon={faPlus}/>
                                        </Link>

                                        <Link
                                            className="btn btn-outline-primary"
                                            to="/episode/add">
                                            <FontAwesomeIcon icon={faEdit}/>
                                        </Link>
                                    </td>
                                </tr>
                            )
                        })}
                        </tbody>
                    </Table>
                    }
                    {name && <p>Hier ist {name}</p>}
                    <Link
                        className="btn btn-outline-primary"
                        to="/people">People
                    </Link>
                </CardBody>
            </Card>
        </Container>
    );
}

export default SeriesList;
