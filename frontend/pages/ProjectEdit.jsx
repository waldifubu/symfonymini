import React, {useState, useEffect} from 'react';
import {Link, useParams, useNavigate} from "react-router-dom";
import Layout from "../components/Layout"
import Swal from 'sweetalert2'
import axios from 'axios';

function ProjectEdit() {
    const [id, setId] = useState('')
    const [name, setName] = useState('');
    const [description, setDescription] = useState('')
    const [slug, setSlug] = useState(useParams().slug)
    const [uuid, setUuid] = useState('')
    const [isSaving, setIsSaving] = useState(false)
    const navigate = useNavigate();

    useEffect(() => {
        axios.get(`/api/project/${slug}`)
            .then(function (response) {
                let project = response.data
                setName(project.name)
                setDescription(project.description)
                setSlug(project.slug)
                setUuid(project.uuid)
            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'An Error Occured!',
                    showConfirmButton: false,
                    timer: 1500
                })
            })

    }, [slug])

    const handleSave = () => {
        setIsSaving(true);
        axios.patch(`/api/project/${slug}`, {
            name: name,
            description: description,
        })
            .then(function (response) {
                const slugRes = response.data.slug;
                setSlug(slugRes)
                navigate(`/edit/${slugRes}`, {replace: true})

                Swal.fire({
                    icon: 'success',
                    title: 'Project updated successfully!',
                    showConfirmButton: false,
                    timer: 1000
                })

                setIsSaving(false)
            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'An Error Occured!',
                    showConfirmButton: false,
                    timer: 1100
                })
                setIsSaving(false)
            });
    }


    const metadata = {
        title: `Edit entry ${slug}`,
        description: 'Edit'
    }
    return (
        <Layout seo={metadata}>
            <div className="container">
                <h2 className="text-center mt-5 mb-3">Edit Project</h2>
                <div className="card">
                    <div className="card-header">
                        <Link
                            className="btn btn-outline-info float-right"
                            to="/">View All Projects
                        </Link>
                    </div>
                    <div className="card-body">
                        <form>
                            <div className="form-group">
                                <label htmlFor="name">Name</label>
                                <input
                                    onChange={(event) => {
                                        setName(event.target.value)
                                    }}
                                    value={name}
                                    type="text"
                                    className="form-control"
                                    id="name"
                                    name="name"/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="description">Description</label>
                                <textarea
                                    value={description}
                                    onChange={(event) => {
                                        setDescription(event.target.value)
                                    }}
                                    className="form-control"
                                    id="description"
                                    rows="3"
                                    name="description"></textarea>
                            </div>
                            <div className="form-group">
                                <label htmlFor="slug">Slug</label>
                                <input readOnly={true}
                                       value={slug}
                                       onChange={(event) => {
                                           setSlug(event.target.value)
                                       }}
                                       className="form-control form-control-sm"
                                       id="slug"
                                       name="slug"></input>
                            </div>
                            <div className="form-group">
                                <label htmlFor="uuid">UUID</label>
                                <input readOnly={true}
                                       value={uuid}
                                       onChange={(event) => {
                                           setUuid(event.target.value)
                                       }}
                                       className="form-control form-control-sm"
                                       id="uuid"
                                       name="uuid"></input>
                            </div>
                            <button
                                disabled={isSaving}
                                onClick={handleSave}
                                type="button"
                                className="btn btn-outline-success mt-3">
                                Update Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </Layout>
    );
}

export default ProjectEdit;