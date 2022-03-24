import React, { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import Button from 'react-bootstrap/Button';
import axios from 'axios';
import Swal from 'sweetalert2'
import Pagination from "react-js-pagination";

export default function Details({match}) {

    const [book, setBook] = useState([])
    const { bookId } = useParams();
    useEffect(()=>{
        fetchBook()
    },[])

    const fetchBook = async (pageNumber = 1) => {
        await window.axios.get(`/api/books/${bookId}`).then(({data})=>{
            console.log(data)
            setBook(data.data)
        })
    }

    return (
        <div className="container">
            <div className="row">
                <div className="col-12">
                    <div className="card card-body">
                        <div className="row">
                            <div className="col-6">
                                <div className="row">
                                    <img src={book.image}/>
                                </div>
                            </div>
                            <div className="col-6">
                                <div className="row">
                                    <div className="col-12">
                                        <span className="fw-bold"> Title:</span> {book.title}
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-12">
                                        <span className="fw-bold"> Author:</span> {book.author}
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-12">
                                        <span className="fw-bold"> No. of pages:</span> {book.no_of_pages}
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-12">
                                        <span className="fw-bold"> Price:</span> {book.compare_at_price && (<strike>$ {book.compare_at_price}</strike>)} $ {book.price}
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-12">
                                        <Button className="btn-primary" onClick={ () => Swal.fire({
                                            icon:"success",
                                            text: 'Thank you for your purchase'
                                        }) }> Buy Now</Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}
