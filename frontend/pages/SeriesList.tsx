import React, {FC, useCallback, useEffect, useState} from "react";
import {Button, Card, CardBody, CardHeader, Container, Table} from "reactstrap";
import axios from "axios";
import Swal from 'sweetalert2'
import {Link} from "react-router-dom";
import {useGlobalContext} from "../components/GlobalContext";

const SeriesList: FC = () => {

    const [seriesList, setSeriesList] = useState<Series[]>([])
    const [isLoading, setIsLoading] = useState(true);
    const {name} = useGlobalContext();

    const metadata = {
        title: 'Show all projects',
        description: 'Show all projects in Symfony'
    }

    class Series {
        id: number;
        title: string;
        author: string;
        created: any;

        constructor(data: { id: number, name: string; author: string, created: any }) {
            this.id = data.id;
            this.title = data.name;
            this.author = data.author
            this.created = data.created
        }
    }

    const fetchSeriesList = useCallback(async () => {
        console.log('Fetch mich')
        await axios.get('/api/series')
            .then(function (response) {
                setSeriesList(response.data);
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
                                "created": "2021-09-01T00:00:00+00:00"
                            }
                        ];
                        // @ts-ignore
                        setSeriesList(array)
                    })
            })
    }, []);


    useEffect(() => {
        fetchSeriesList();
    }, [fetchSeriesList]);


    return (
        <Container>
            <Card>
                <CardHeader>
                    <Link
                        className="btn btn-outline-primary"
                        to="/create">Create new podcast
                    </Link>

                    {/*<Link
                        className="btn btn-outline-primary"
                        to="/create">Create New Project
                    </Link>*/}
                </CardHeader>
                <CardBody>
                    {seriesList.length > 0 && <Table className="" hover={true}>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Erstellt</th>
                        </tr>
                        </thead>
                        <tbody>
                        {seriesList.map((series: Series, key: number) => {
                            return (
                                <tr key={key}>
                                    <td>{series.id}</td>
                                    <td>{series.title}</td>
                                    <td>{series.author}</td>
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