// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore, collection, addDoc, onSnapshot, query, orderBy, serverTimestamp, doc, setDoc, updateDoc, deleteDoc } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
import { getStorage, ref, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-storage.js";

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyCCB_oZgOEp12l_ZGcfKXwfYcl8b9IOLDk",
  authDomain: "fyp-chat-system-80851.firebaseapp.com",
  projectId: "fyp-chat-system-80851",
  storageBucket: "fyp-chat-system-80851.firebasestorage.app",
  messagingSenderId: "140849661972",
  appId: "1:140849661972:web:582ece9a9bb49ee1c8ff01",
  measurementId: "G-PED507S03V"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const storage = getStorage(app);
storage.maxUploadRetryTime = 5000; // Fail after 5 seconds if bucket doesn't exist

export { db, storage, collection, addDoc, onSnapshot, query, orderBy, serverTimestamp, doc, setDoc, updateDoc, deleteDoc, ref, uploadBytes, getDownloadURL };
