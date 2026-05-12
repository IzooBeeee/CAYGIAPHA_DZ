export const AVATAR_VERSION = 'v2';

const getAvatarBg = (gender?: string) => {
  if (gender === 'male') return 'bg-sky-400';
  if (gender === 'female') return 'bg-rose-400';
  return 'bg-stone-400';
};

export default function DefaultAvatar({
  gender,
  size = 64,
}: {
  gender?: string | null;
  size?: number;
}) {
  if (gender === 'male') {
    return (
      <img
        src={`/avatar/${AVATAR_VERSION}/male.svg`}
        alt="Male"
        className={`w-full h-full object-cover ${getAvatarBg(gender)}`}
        width={size}
        height={size}
      />
    );
  }

  return (
    <img
      src={`/avatar/${AVATAR_VERSION}/female.svg`}
      alt="Female"
      className="w-full h-full object-cover"
      width={size}
      height={size}
    />
  );
}
