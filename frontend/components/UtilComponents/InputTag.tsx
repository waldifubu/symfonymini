import React from "react";
import { WithContext as ReactTags, Tag } from "react-tag-input";
import "./inputTag.css";

interface InputTagProps {
    tags: Tag[];
    handleDelete: (i: number) => void;
    handleAddition: (tag: Tag) => void;
    onClearAll: () => void;
}

const KeyCodes = {
    comma: 188,
    enter: 13,
};

const delimiters = [KeyCodes.comma, KeyCodes.enter];

const InputTag: React.FC<InputTagProps> = ({ tags, handleDelete, handleAddition, onClearAll }) => {
    return (
        <div id="tags">
            <ReactTags
                tags={tags}
                delimiters={delimiters}
                handleDelete={handleDelete}
                handleAddition={handleAddition}
                inputFieldPosition="bottom"
                autocomplete
                allowDragDrop={true}
                clearAll
                onClearAll={onClearAll}
            />
        </div>
    );
};

export default InputTag;