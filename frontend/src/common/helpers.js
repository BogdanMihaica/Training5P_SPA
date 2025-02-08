/* eslint-disable no-unused-vars */
import axios from "axios";

/**
 * Returns the value of a specified cookie
 * 
 * @param {String} name 
 * 
 * @returns {String}
 */
export function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}