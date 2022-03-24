import React, { useState } from "react";
import ReactDOM from 'react-dom';
import Form from 'react-bootstrap/Form'
import Button from 'react-bootstrap/Button'
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import axios from 'axios'
import Swal from 'sweetalert2';
import { useNavigate } from 'react-router-dom'

export default function CreateBook() {
    const navigate = useNavigate();

    const [name, setName] = useState("")
    const [price, setPrice] = useState("")
    const [compareAtPrice, setCompareAtPrice] = useState("")
    const [image, setImage] = useState()
    const [description, setDescription] = useState("")
    const [noOfPages, setNoOfPages] = useState("")
    const [author, setAuthor] = useState("")
    const [wholesalePrice, setWholesalePrice] = useState("")
    const [validationError,setValidationError] = useState({})


    const changeHandler = (event) => {
        setImage(event.target.files[0]);
    };

    const createBook = async (e) => {
        e.preventDefault();

        const formData = new FormData();

        let errors = {};
        if (name === '') {
            errors.name = 'Book title is required';
        }
        if (author === '') {
            errors.author = 'Book author is required';
        }
        if (price === '') {
            errors.price = 'Book price is required';
        }
        if (price >= compareAtPrice) {
            errors.compareAtPrice = 'Book price should be less than compare at price';
        }
        if (noOfPages === '') {
            errors.pages = 'Number of pages is required';
        }
        if (wholesalePrice === '') {
            errors.wholesalePrice = 'Wholesale Price is required';
        }
        if (description === '') {
            errors.description = 'Description is required';
        }

        if (Object.entries(errors).length) {
            setValidationError(errors);
            return false;
        }
        formData.append('title', name);
        formData.append('author', author);
        formData.append('price', price);
        formData.append('compare_at_price', compareAtPrice);
        formData.append('no_of_pages', noOfPages);
        formData.append('wholesale_price', wholesalePrice);
        formData.append('body_html', description);
        formData.append('image', image);

        await window.axios.post(`/api/books`, formData).then(({data})=>{
            Swal.fire({
                icon:"success",
                text: 'Book created successfully'
            });
            // navigate("/")
        }).catch(({response})=>{
            if(response.status===422){
                setValidationError(response.data.errors)
            }else{
                Swal.fire({
                    text:response.data.message,
                    icon:"error"
                })
            }
        })
    }

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-12 col-sm-12 col-md-6">
                    <div className="card">
                        <div className="card-body">
                            <h4 className="card-title">Create Book</h4>
                            <hr />
                            <div className="form-wrapper">
                                {
                                    Object.keys(validationError).length > 0 && (
                                        <div className="row">
                                            <div className="col-12">
                                                <div className="alert alert-danger">
                                                    <ul className="mb-0">
                                                        {
                                                            Object.entries(validationError).map(([key, value])=>(
                                                                <li key={key}>{value}</li>
                                                            ))
                                                        }
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    )
                                }
                                <Form onSubmit={createBook}>
                                    <Row>
                                        <Col>
                                            <Form.Group controlId="Name">
                                                <Form.Label>Name</Form.Label>
                                                <Form.Control type="text" value={name} onChange={(event)=>{
                                                    setName(event.target.value)
                                                }} isInvalid={validationError.name}/>
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Row>
                                        <Col>
                                            <Form.Group controlId="Author">
                                                <Form.Label>Author</Form.Label>
                                                <Form.Control type="text" value={author} onChange={(event)=>{
                                                    setAuthor(event.target.value)
                                                }} isInvalid={validationError.author}/>
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Row className="my-3">
                                        <Col>
                                            <Form.Group controlId="Description">
                                                <Form.Label>Description</Form.Label>
                                                <Form.Control as="textarea" rows={3} value={description} onChange={(event)=>{
                                                    setDescription(event.target.value)
                                                }} isInvalid={validationError.description}/>
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Row>
                                        <Col>
                                            <Form.Group controlId="Price">
                                                <Form.Label>Price</Form.Label>
                                                <Form.Control type="number" value={price} onChange={(event)=>{
                                                    setPrice(event.target.value)
                                                }} isInvalid={validationError.price}/>
                                            </Form.Group>
                                        </Col>
                                        <Col>
                                            <Form.Group controlId="CompareAtPrice">
                                                <Form.Label>Compare At Price</Form.Label>
                                                <Form.Control type="number" value={compareAtPrice} onChange={(event)=>{
                                                    setCompareAtPrice(event.target.value)
                                                }} isInvalid={validationError.compareAtPrice}/>
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Row>
                                        <Col>
                                            <Form.Group controlId="NoOfPages">
                                                <Form.Label>Number of pages</Form.Label>
                                                <Form.Control type="number" value={noOfPages} onChange={(event)=>{
                                                    setNoOfPages(event.target.value)
                                                }} isInvalid={validationError.pages}/>
                                            </Form.Group>
                                        </Col>
                                        <Col>
                                            <Form.Group controlId="WholesalePrice">
                                                <Form.Label>Wholesale Price</Form.Label>
                                                <Form.Control type="text" value={wholesalePrice} onChange={(event)=>{
                                                    setWholesalePrice(event.target.value)
                                                }}  isInvalid={validationError.wholesalePrice}/>
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Row>
                                        <Col>
                                            <Form.Group controlId="Image" className="mb-3">
                                                <Form.Label>Image</Form.Label>
                                                <Form.Control type="file" onChange={changeHandler} />
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Button variant="primary" className="mt-2" size="lg" block="block" type="submit">
                                        Save
                                    </Button>
                                </Form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}
