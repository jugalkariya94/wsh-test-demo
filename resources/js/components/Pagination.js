// Need some customization. Not completed right now but leaving for just a demo and further refactoring.

import React from 'react'
import Button from 'react-bootstrap/Button';

export default function Pagination ({itemsPerPage, totalItems, paginate, currentPage}) {
  const pageNumbers = []
  for (let i = 1; i <= Math.ceil(totalItems / itemsPerPage); i++) {
    pageNumbers.push(i)
  }
  const paginationList = pageNumbers.map(number => (
    <li key={number} className='page-item'>
      <a onClick={() => paginate(number)} className='page-link'>
        {number}
      </a>
    </li>
  ))
  return (
    <div>
      <Button onClick={() => paginate(currentPage - 1)} disabled={currentPage === 1}>Previous</Button>
      <div>Current Page: {currentPage}</div>
        <div>{ paginationList}</div>
      <Button onClick={() => paginate(currentPage + 1)} disabled={currentPage === pageNumbers.length}>Next</Button>
    </div>
  )
}
