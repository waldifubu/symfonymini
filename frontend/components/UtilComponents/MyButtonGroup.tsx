import {Button, ButtonGroup} from "reactstrap";
import React from "react";

const MyButtonGroup = (props: any) => {
    const {funcPos, funcNeg, value, className} = props;

    return (
        <ButtonGroup>
            <Button
                className={className}
                name="blocked"
                color="success"
                outline
                onClick={funcPos}
                active={value}
            >
                Yes
            </Button>
            <Button
                className={className}
                name="blocked"
                color="danger"
                outline
                onClick={funcNeg}
                active={!value}
            >
                No
            </Button>
        </ButtonGroup>
    );
};

export default MyButtonGroup;
