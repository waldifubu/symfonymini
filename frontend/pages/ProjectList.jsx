import React, {useEffect, useState} from 'react';
import {Link, Outlet} from "react-router-dom";
import Layout from "../components/Layout.jsx"
import Swal from 'sweetalert2'
import axios from 'axios';

function ProjectList() {
    const [projectList, setProjectList] = useState([])
    const metadata = {
        title: 'Show all projects',
        description: 'Show all projects in Symfony'
    }

    useEffect(() => {
        fetchProjectList()
    }, [])

    const fetchProjectList = () => {
        axios.get('/api/project')
            .then(function (response) {
                setProjectList(response.data);
            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: error,
                    showConfirmButton: false,
                    timer: 2100
                })
                    .then(() => {
                        const array = [
                            {
                                "id": 1,
                                "name": "waldi",
                                "description": "fallback",
                                "slug": "null"
                            }
                        ]
                        setProjectList(array)
                    })
            })
    }

    const handleDelete = (slug) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/api/project/${slug}`)
                    .then(function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Project deleted successfully!',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        fetchProjectList()
                    })
                    .catch(function (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'An Error Occured!',
                            showConfirmButton: false,
                            timer: 1100
                        })
                    });
            }
        })
    }

    return (
        <Layout seo={metadata}>
            <div className="container">
                <h2 className="text-center mt-5 mb-3">Symfony Project Manager</h2>
                <div className="card">
                    <div className="card-header">
                        <Link
                            className="btn btn-outline-primary"
                            to="/create">Create New Project
                        </Link>
                    </div>
                    <div className="card-body">
                        {projectList.length > 0 &&
                            <table className="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th width="240">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                {projectList.map((project, key) => {
                                    return (
                                        <tr key={key}>
                                            <td>{project.name}</td>
                                            <td>{project.description}</td>
                                            <td>
                                                <Link
                                                    to={`/show/${project.uuid}`}
                                                    className="btn btn-outline-info mx-1">
                                                    Show
                                                </Link>
                                                <Link
                                                    className="btn btn-outline-success mx-1"
                                                    to={`/edit/${project.uuid}`}>
                                                    Edit
                                                </Link>
                                                <button
                                                    onClick={() => handleDelete(project.slug)}
                                                    className="btn btn-outline-danger mx-1">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    )
                                })}
                                </tbody>
                            </table>
                        }
                    </div>
                </div>
            </div>
        </Layout>
    );
}

export default ProjectList;