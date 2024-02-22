import React from 'react';
import './EmbedYoutube.css'

const EmbedYoutube = ({ url }) => (
    <div className="video-responsive">
      <iframe
          width="853"
          height="480"
          src={url}
          allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowFullScreen
          title="Embedded youtube"
      />
    </div>
);

export default EmbedYoutube;