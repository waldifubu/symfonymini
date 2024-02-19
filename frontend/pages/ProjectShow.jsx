import React, {useEffect, useState} from 'react';
import {Link, useParams} from "react-router-dom";
import Layout from "../components/Layout"
import axios from 'axios';

function ProjectShow() {
    const [id, setId] = useState(useParams().slug)
    const [project, setProject] = useState({name: '', description: ''})
    const metaData = {
        title: `Show project ${project.name}`,
        description: 'Show details'
    }

    useEffect(() => {
        axios.get(`/api/project/${id}`)
            .then(function (response) {
                setProject(response.data)
            })
            .catch(function (error) {
                console.log(error);
            })
    }, [])

    return (
        <Layout seo={metaData}>
            <div className="container">
                <h2 className="text-center mt-5 mb-3">Show Project details &quot;{project.name}&quot;</h2>
                <div className="card">
                    <div className="card-header">
                        <Link
                            className="btn btn-outline-info float-start"
                            to="/"> View All Projects
                        </Link>

                        <Link
                            className="btn btn-outline-info float-end"
                            to={`/edit/${project.slug}`}>
                            Edit Project
                        </Link>
                    </div>
                    <div className="card-body">
                        <b className="text-muted">Name:</b>
                        <p>{project.name}</p>
                        <b className="text-muted">Description:</b>
                        <p>{project.description}</p>
                        <b className="text-muted">Slug:</b>
                        <p>{project.slug}</p>
                        <b className="text-muted">UUID:</b>
                        <p>{project.uuid}</p>
                    </div>
                </div>
            </div>
        </Layout>
    );
}

// title={`Show project ${id}`}
export default ProjectShow;