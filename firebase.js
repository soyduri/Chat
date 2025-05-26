// firebase.js
import { initializeApp } from "firebase/app";
import { getFirestore } from "firebase/firestore";
import { getAuth } from "firebase/auth"; // 👈 Importar auth

// Configuración de Firebase
const firebaseConfig = {
  apiKey: "AIzaSyCfNfZTazJ21CFKtUmBs8kTMnxYoMTjCKY",
  authDomain: "darkshapp-faf71.firebaseapp.com",
  projectId: "darkshapp-faf71",
  storageBucket: "darkshapp-faf71.firebasestorage.app",
  messagingSenderId: "500416638093",
  appId: "1:500416638093:web:fef74555199a4230d546c4",
};

// Inicializar Firebase
const app = initializeApp(firebaseConfig);

// Exportar Firestore y Auth
export const db = getFirestore(app);
export const auth = getAuth(app); // 👈 Exportar auth también
