import React from "react";
import ReactDOM from "react-dom";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import HeaderNav from "./HeaderNav";
import CreateBook from "./Book/Create";
import ListBook from "./Book/List";
import DetailsPage from "./Book/Details";
import NotFoundPage from "./NotFoundPage";

const Book = () => {
    return (
        <>
            <Router>
                <HeaderNav />
                <Routes>
                    <Route path="/" exact={true} element={ <ListBook /> } />
                    <Route path="/book/create" element={ <CreateBook /> } />

                    <Route path="/book/:bookId" exact element={ <DetailsPage />} />
                    <Route path="*" element={ <NotFoundPage /> } />
                </Routes>
            </Router>
        </>
    );
}

export default Book;
// DOM element
if (document.getElementById("app")) {
    ReactDOM.render(<Book />, document.getElementById("app"));
}
