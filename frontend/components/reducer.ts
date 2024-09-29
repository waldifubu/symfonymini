
import {ADD_ITEM, CLEAR_LIST, REMOVE_ITEM, RESET_LIST} from "./actions";
import {data} from "../pages/Example";

// @ts-ignore
export const reducer: (state: object, action: any) => ({ people: any[] }) = (state: object, action: any) => {
    switch (action.type) {
        case CLEAR_LIST:
            return {...state, people: []}
        case RESET_LIST:
            return {...state, people: data}
        case REMOVE_ITEM:
            // @ts-ignore
            let newPeople = state.people.filter((person) => person.id !== action.payload.id);
            return {...state, people: newPeople};
        case ADD_ITEM:
            // @ts-ignore
            const id = Date.now(); //  Math.floor(Math.random() * 10) + 5;
            // @ts-ignore
            let newPeople2 = state.people;
            newPeople2.push({
                "id": id,
                "name": action.payload.name
            })
            return {...state, people: newPeople2};
    }
}


export default reducer;