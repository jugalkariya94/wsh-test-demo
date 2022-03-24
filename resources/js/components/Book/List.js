import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Pagination from "react-js-pagination";

export default function List() {

    const [books, setBooks] = useState([])
    const [totalBooks, setTotalBooks] = useState(0)
    const [currentPage, setCurrentPage] = React.useState(1)
    const [itemsPerPage, setItemsPerPage] = React.useState(2)

    useEffect(()=>{
        fetchBooks()
    },[])

    const fetchBooks = async (pageNumber = 1) => {
        await window.axios.get(`/api/books?page=${pageNumber}`).then(({data})=>{
            setBooks(data.data)
            setTotalBooks(data.meta.total)
            setItemsPerPage(data.meta.per_page)
            setCurrentPage(pageNumber)
        })
    }

    return (
        <div className="container">
            <div className="row">
                <div className='col-12'>
                    <Link className='btn btn-primary mb-2 float-end' to={"/book/create"}>
                        Create Book
                    </Link>
                </div>
                <div className="col-12">
                    <div className="card card-body">
                        <div className="table-responsive">
                            <table className="table table-bordered mb-0 text-center">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                {
                                    books.length > 0 ? (
                                        books.map((book, key)=>(
                                            <tr key={key}>
                                                <td>{book.title}</td>
                                                <td>{book.author}</td>
                                                <td>
                                                    {book.compare_at_price && (<strike>$ {book.compare_at_price}</strike>)} $ {book.price}
                                                </td>
                                                <td>
                                                    <Link to={`/book/${book.shopify_id}`} className='btn btn-success me-2'>
                                                        View
                                                    </Link>
                                                    {/*<Button variant="danger" onClick={()=>deleteProduct(row.id)}>*/}
                                                    {/*    Delete*/}
                                                    {/*</Button>*/}
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan={4}>No Books Available</td>
                                        </tr>
                                    )
                                }
                                </tbody>
                            </table>
                            <Pagination
                                activePage={currentPage}
                                itemsCountPerPage={itemsPerPage}
                                totalItemsCount={totalBooks}
                                onChange={(pageNumber) => {
                                    fetchBooks(pageNumber)
                                }}
                                pageRangeDisplayed={8}
                                itemClass="page-item"
                                linkClass="page-link"
                                firstPageText="⟨⟨"
                                lastPageText="⟩⟩"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}
