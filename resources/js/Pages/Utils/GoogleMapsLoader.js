import { Loader } from "@googlemaps/js-api-loader";

let loader

export const initializeLoader = (apiKey) => {
    if(!loader) {
        loader = new Loader({
            apiKey: apiKey,
            version: "weekly",
            libraries: ["places", "maps", "marker"],
        })
    }
    return loader
}

export const getLoader = () => {
    if(!loader) {
        throw new Error("Loader not initialized bruh")
    }
    return loader
}   
