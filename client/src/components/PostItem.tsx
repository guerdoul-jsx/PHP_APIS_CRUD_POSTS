import React from "react";

const PostItem = ({ post }) => {
  return (
    <div
      tabIndex={0}
      className="collapse collapse-arrow border border-base-300 bg-base-200"
      key={post.id}
    >
      <div className="collapse-title text-xl font-medium flex items-center justify-between">
        {post.title} <button className="btn btn-xs">delete</button>
      </div>
      <div className="collapse-content">
        <p>{post.body}</p>
      </div>
    </div>
  );
};

export default PostItem;
