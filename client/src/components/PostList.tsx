import React from "react";
import PostItem from "./PostItem";

const PostList = ({ data }) => {
  return (
    <div className="flex flex-col space-y-4">
      {data.map((post) => (
        <PostItem post={post} />
      ))}
    </div>
  );
};

export default PostList;
