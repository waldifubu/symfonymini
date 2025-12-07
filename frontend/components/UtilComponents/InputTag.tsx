import React from "react";
import {KEYS, SEPARATORS, Tag, WithContext as ReactTags} from "react-tag-input";
import "./inputTag.css";

interface InputTagProps {
    tags: Tag[];
    handleDelete: (i: number) => void;
    handleAddition: (tag: Tag) => void;
    onClearAll: () => void;
}

const InputTag: React.FC<InputTagProps> = ({tags, handleDelete, handleAddition, onClearAll}: InputTagProps): React.JSX.Element => {
    return (
        <div id="tags">
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
