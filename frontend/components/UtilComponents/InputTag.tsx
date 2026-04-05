import React from "react";
import {SEPARATORS, Tag, WithContext as ReactTags} from "react-tag-input";
import "./inputTag.css";

interface InputTagProps {
    tags: Tag[];
    setTags: React.Dispatch<React.SetStateAction<Tag[]>>;
    name: string;
}

const InputTag: React.FC<InputTagProps> = ({tags, setTags, name}): React.JSX.Element => {

    const handleDelete = (i: number) => {
        setTags(tags.filter((_tag: Tag, index: number) => index !== i));
    };

    const handleAddition = (tag: Tag) => {
        setTags([...tags, tag]);
    };

    const onClearAll = () => {
        setTags([]);
    };

    return (
        <div id="tags">
            <input type="hidden" name={name} value={tags.map(tag => tag.text).join(', ')}/>
            <ReactTags
                tags={tags}
                separators={[SEPARATORS.ENTER, SEPARATORS.COMMA]}
                handleDelete={handleDelete}
                handleAddition={handleAddition}
                inputFieldPosition="bottom"
                allowDragDrop={true}
                clearAll
                onClearAll={onClearAll}
            />
        </div>
    );
};

export default InputTag;
