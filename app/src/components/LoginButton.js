import React from 'react'
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link
  } from "react-router-dom";

export default function LoginButton() {
    return (
        <>
        <Link
            to="/login"
            type="button"
            className="bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
        >
            Login
            {/* <span className="sr-only">View notifications</span>
            <BellIcon className="h-6 w-6" aria-hidden="true" /> */}
        </Link>
        </>
    )
}
