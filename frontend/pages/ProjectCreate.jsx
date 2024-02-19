import {useEffect, useState} from 'react';
import {Link, useNavigate} from "react-router-dom";
import Layout from "../components/Layout"
import Swal from 'sweetalert2'
import axios from 'axios';

function ProjectCreate() {
    const [name, setName] = useState('');
    const [description, setDescription] = useState('')
    const [isSaving, setIsSaving] = useState(false)
    const [isRedirect, setIsRedirect] = useState(false)
    const navigate = useNavigate();

    const handleSave = () => {
        setIsSaving(true);
        let formData = new FormData()
        if (name.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Please type in project name',
                showConfirmButton: false,
                timer: 1300
            }).then(() => {
                setIsRedirect(false);
                setIsSaving(false)
            });
            return;
        }

        formData.append("name", name)
        formData.append("description", description)
        axios.post('/api/project', formData)
            .then(function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Project saved successfully!',
                    showConfirmButton: false,
                    timer: 1300
                }).then(() => {
                        console.log(response)
                        //setIsRedirect(true)
                        navigate("/");
                    }
                )
            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'An Error Occured! ' + error,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                        setIsSaving(false)
                    }
                );
            });
    }

    // redirect user upon succession
    useEffect(() => {
        (() => {
            if (isRedirect) {
                navigate('/');
            }
        })()
    }, [isRedirect])

    const metadata = {
        title: 'Create project',
        description: 'Create'
    };
    return (
        <Layout seo={metadata}>
            <div className="container">
                <h2 className="text-center mt-5 mb-3">Create New Project</h2>
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
                                <input required="required"
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
                            <button
                                disabled={isSaving}
                                onClick={handleSave}
                                type="button"
                                className="btn btn-outline-primary mt-3">
                                Save Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </Layout>

    );
}

export default ProjectCreate;