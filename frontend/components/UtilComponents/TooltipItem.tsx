import React, {useState} from "react";
import {Tooltip} from "reactstrap";

const TooltipItem = (props: any) => {
    const {position, target, text} = props;
    const [tooltipOpen, setTooltipOpen] = useState(false);
    const toggle = () => setTooltipOpen(!tooltipOpen);

    return (
        <span className={"py-5"}>
            <Tooltip
                placement={position}
                isOpen={tooltipOpen}
                target={target}
                toggle={toggle}
                fade={false}
            >
        {text}
      </Tooltip>
    </span>
    );
};

export default TooltipItem;
